<?php

namespace App\Http\Controllers\Admin\FinalProject;

use App\Http\Controllers\Controller;
use App\Helpers\NotificationHelper;
use App\Models\FinalProjectDocument;
use App\Models\FinalProjectDefense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DefenseApprovalController extends Controller
{
    private function canManageAll(): bool
    {
        $role = User::normalizeRole(auth()->user()?->role);
        return in_array($role, ['superadmin', 'masteradmin'], true);
    }

    public function index()
    {
        $lecturerId = auth()->id();
        $role = User::normalizeRole(auth()->user()?->role);
        
        $defenses = FinalProjectDefense::with(['finalProject.student', 'finalProject.documents'])
            ->when(!$this->canManageAll(), function ($q) use ($lecturerId) {
                $q->whereHas('finalProject', function ($qq) use ($lecturerId) {
                    $qq->bySupervisor($lecturerId);
                });
            })
            ->pending()
            ->orderBy('registered_at', 'asc')
            ->get();

        return view('admin.final-project.defenses.index', compact('defenses', 'role'));
    }

    public function show($id)
    {
        $lecturerId = auth()->id();
        
        $defense = FinalProjectDefense::with([
            'finalProject.student', 
            'finalProject.supervisor1', 
            'finalProject.supervisor2',
            'finalProject.documents',
            'finalProject.guidanceLogs' => function($q) use ($lecturerId) {
                $q->where('supervisor_id', $lecturerId)->approved();
            }
        ])->when(!$this->canManageAll(), function ($q) use ($lecturerId) {
            $q->whereHas('finalProject', function ($qq) use ($lecturerId) {
                $qq->bySupervisor($lecturerId);
            });
        })->findOrFail($id);

        return view('admin.final-project.defenses.show', compact('defense'));
    }

    public function approve(Request $request, $id)
    {
        $lecturerId = auth()->id();
        
        $defense = FinalProjectDefense::when(!$this->canManageAll(), function ($q) use ($lecturerId) {
            $q->whereHas('finalProject', function ($qq) use ($lecturerId) {
                $qq->bySupervisor($lecturerId);
            });
        })->findOrFail($id);

        $rules = [
            'approval_notes' => 'nullable|string',
        ];

        // Jadwal hanya ditentukan oleh Kaprodi/Superuser (bukan dosen pembimbing).
        if ($this->canManageAll()) {
            $rules['scheduled_at'] = 'nullable|date_format:Y-m-d\TH:i';
        }

        $request->validate($rules);

        $scheduledAt = $defense->scheduled_at;
        if ($this->canManageAll() && $request->filled('scheduled_at')) {
            $scheduledAt = Carbon::parse($request->scheduled_at);
        }

        $defense->update([
            'status' => 'approved',
            'approved_by' => $lecturerId,
            'approved_at' => now(),
            'approval_notes' => $request->approval_notes,
            'scheduled_at' => $scheduledAt,
        ]);

        // Jika pengajuan sidang disetujui, dokumen-dokumen sidang yang terkait ikut disetujui juga.
        FinalProjectDocument::query()
            ->where('final_project_id', $defense->final_project_id)
            ->whereIn('document_type', ['final', 'presentation'])
            ->whereIn('review_status', ['pending', 'needs_revision'])
            ->update([
                'review_status' => 'approved',
                'reviewer_id' => $lecturerId,
                'review_notes' => 'Auto-approved saat pengajuan Sidang disetujui.',
                'reviewed_at' => now(),
            ]);

        $studentId = (int) data_get($defense, 'finalProject.student_id');
        if ($studentId > 0) {
            $when = $defense->scheduled_at ? Carbon::parse($defense->scheduled_at)->translatedFormat('d M Y H:i') : null;
            $msg = $when
                ? "Sidang Anda sudah disetujui. Jadwal: {$when}."
                : "Sidang Anda sudah disetujui. Jadwal akan diinformasikan oleh Kaprodi.";

            NotificationHelper::notifyStudent(
                $studentId,
                'sidang.approved',
                'Sidang Disetujui',
                $msg,
                route('student.final-project.index'),
                ['scheduled_at' => $defense->scheduled_at]
            );
        }

        return redirect()->route('admin.final-project.defenses.index')
            ->with('success', 'Pendaftaran sidang TA berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $lecturerId = auth()->id();
        
        $defense = FinalProjectDefense::when(!$this->canManageAll(), function ($q) use ($lecturerId) {
            $q->whereHas('finalProject', function ($qq) use ($lecturerId) {
                $qq->bySupervisor($lecturerId);
            });
        })->findOrFail($id);

        $request->validate([
            'approval_notes' => 'required|string',
        ]);

        $defense->update([
            'status' => 'rejected',
            'approved_by' => $lecturerId,
            'approved_at' => now(),
            'approval_notes' => $request->approval_notes,
        ]);

        $studentId = (int) data_get($defense, 'finalProject.student_id');
        if ($studentId > 0) {
            $msg = "Sidang Anda ditolak. Catatan: " . (string) $defense->approval_notes;
            NotificationHelper::notifyStudent(
                $studentId,
                'sidang.rejected',
                'Sidang Ditolak',
                $msg,
                route('student.final-project.index')
            );
        }

        return redirect()->route('admin.final-project.defenses.index')
            ->with('success', 'Pendaftaran sidang TA ditolak.');
    }
}
