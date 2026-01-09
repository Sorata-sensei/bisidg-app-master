<?php

namespace App\Http\Controllers\Admin\FinalProject;

use App\Http\Controllers\Controller;
use App\Helpers\NotificationHelper;
use App\Models\FinalProjectDocument;
use App\Models\FinalProjectProposal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProposalApprovalController extends Controller
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
        
        $proposals = FinalProjectProposal::with(['finalProject.student', 'finalProject.documents'])
            ->when(!$this->canManageAll(), function ($q) use ($lecturerId) {
                $q->whereHas('finalProject', function ($qq) use ($lecturerId) {
                    $qq->bySupervisor($lecturerId);
                });
            })
            ->pending()
            ->orderBy('registered_at', 'asc')
            ->get();

        return view('admin.final-project.proposals.index', compact('proposals', 'role'));
    }

    public function show($id)
    {
        $lecturerId = auth()->id();
        
        $proposal = FinalProjectProposal::with([
            'finalProject.student', 
            'finalProject.supervisor1', 
            'finalProject.supervisor2',
            'finalProject.documents' => function($q) {
                $q->where('document_type', 'proposal');
            },
            'finalProject.guidanceLogs' => function($q) use ($lecturerId) {
                $q->where('supervisor_id', $lecturerId)->approved();
            }
        ])->when(!$this->canManageAll(), function ($q) use ($lecturerId) {
            $q->whereHas('finalProject', function ($qq) use ($lecturerId) {
                $qq->bySupervisor($lecturerId);
            });
        })->findOrFail($id);

        return view('admin.final-project.proposals.show', compact('proposal'));
    }

    public function approve(Request $request, $id)
    {
        $lecturerId = auth()->id();
        
        $proposal = FinalProjectProposal::when(!$this->canManageAll(), function ($q) use ($lecturerId) {
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

        $scheduledAt = $proposal->scheduled_at;
        if ($this->canManageAll() && $request->filled('scheduled_at')) {
            $scheduledAt = Carbon::parse($request->scheduled_at);
        }

        $proposal->update([
            'status' => 'approved',
            'approved_by' => $lecturerId,
            'approved_at' => now(),
            'approval_notes' => $request->approval_notes,
            'scheduled_at' => $scheduledAt,
        ]);

        $proposal->finalProject()->update(['status' => 'research']);

        // Jika pengajuan sempro disetujui, dokumen-dokumen sempro yang terkait ikut disetujui juga.
        FinalProjectDocument::query()
            ->where('final_project_id', $proposal->final_project_id)
            ->where('document_type', 'proposal')
            ->whereIn('review_status', ['pending', 'needs_revision'])
            ->update([
                'review_status' => 'approved',
                'reviewer_id' => $lecturerId,
                'review_notes' => 'Auto-approved saat pengajuan Sempro disetujui.',
                'reviewed_at' => now(),
            ]);

        $studentId = (int) data_get($proposal, 'finalProject.student_id');
        if ($studentId > 0) {
            $when = $proposal->scheduled_at ? Carbon::parse($proposal->scheduled_at)->translatedFormat('d M Y H:i') : null;
            $msg = $when
                ? "Sempro Anda sudah disetujui. Jadwal: {$when}."
                : "Sempro Anda sudah disetujui. Jadwal akan diinformasikan oleh Kaprodi.";

            NotificationHelper::notifyStudent(
                $studentId,
                'sempro.approved',
                'Seminar Proposal Disetujui',
                $msg,
                route('student.final-project.index'),
                ['scheduled_at' => $proposal->scheduled_at]
            );
        }

        return redirect()->route('admin.final-project.proposals.index')
            ->with('success', 'Pendaftaran seminar proposal berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $lecturerId = auth()->id();
        
        $proposal = FinalProjectProposal::when(!$this->canManageAll(), function ($q) use ($lecturerId) {
            $q->whereHas('finalProject', function ($qq) use ($lecturerId) {
                $qq->bySupervisor($lecturerId);
            });
        })->findOrFail($id);

        $request->validate([
            'approval_notes' => 'required|string',
        ]);

        $proposal->update([
            'status' => 'rejected',
            'approved_by' => $lecturerId,
            'approved_at' => now(),
            'approval_notes' => $request->approval_notes,
        ]);

        $studentId = (int) data_get($proposal, 'finalProject.student_id');
        if ($studentId > 0) {
            $msg = "Sempro Anda ditolak. Catatan: " . (string) $proposal->approval_notes;
            NotificationHelper::notifyStudent(
                $studentId,
                'sempro.rejected',
                'Seminar Proposal Ditolak',
                $msg,
                route('student.final-project.index')
            );
        }

        return redirect()->route('admin.final-project.proposals.index')
            ->with('success', 'Pendaftaran seminar proposal ditolak.');
    }
}
