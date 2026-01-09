<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Helpers\NotificationHelper;
use App\Models\FinalProject;
use App\Models\FinalProjectDefense;
use App\Models\Student;
use Illuminate\Http\Request;

class DefenseRegistrationController extends Controller
{
    public function create()
    {
        $studentId = decrypt(session('student_id'));
        $finalProject = FinalProject::with('proposal')->where('student_id', $studentId)->firstOrFail();
        
        // Check if proposal is approved
        if (!$finalProject->proposal || $finalProject->proposal->status !== 'approved') {
            return redirect()->route('student.final-project.index')
                ->with('error', 'Anda harus menyelesaikan seminar proposal terlebih dahulu.');
        }

        // Check if already has defense
        if ($finalProject->defense) {
            return redirect()->route('student.final-project.defense.show', $finalProject->defense->id)
                ->with('info', 'Anda sudah pernah mendaftar sidang TA.');
        }

        return view('students.final-project.defense.create', compact('finalProject'));
    }

    public function store(Request $request)
    {
        $studentId = decrypt(session('student_id'));
        $finalProject = FinalProject::where('student_id', $studentId)->firstOrFail();

        $request->validate([
            // Jadwal sidang ditentukan oleh kaprodi saat approval (bukan oleh mahasiswa).
            'final_draft_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'nik' => ['required','digits:16'],
            'nisn' => ['required','string','max:20'],
            'tempat_lahir' => ['required','string','max:100'],
            'tanggal_lahir' => ['required','date'],
            'nama_ibu_kandung' => ['required','string','max:200'],
            'no_telepon' => ['required','string','max:15'],
        ]);

        try {
            // Simpan/update biodata mahasiswa
            $student = Student::findOrFail($studentId);
            $student->update([
                'nik' => $request->nik,
                'nisn' => $request->nisn,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'nama_ibu_kandung' => $request->nama_ibu_kandung,
                'no_telepon' => $request->no_telepon,
            ]);

            // Create defense
            $defense = FinalProjectDefense::create([
                'final_project_id' => $finalProject->id,
                'registered_at' => now(),
                'scheduled_at' => null,
                'status' => 'pending',
            ]);

            // Upload files
            if ($request->hasFile('final_draft_file')) {
                $path = $request->file('final_draft_file')->store("final-projects/{$studentId}/defense", 'public');
                $finalProject->documents()->create([
                    'document_type' => 'final',
                    'title' => 'Draft Final TA',
                    'file_path' => $path,
                    'version' => 1,
                    'uploaded_by' => $studentId,
                    'uploaded_at' => now(),
                ]);
            }

            // Update status
            $finalProject->update(['status' => 'defense']);

            // Notifikasi ke Kaprodi/Superuser (berdasarkan prodi) + pembimbing (jika ada)
            $prodi = $student?->program_studi;
            $toUserIds = NotificationHelper::kaprodiAndSuperuserUserIdsForProdi($prodi);
            if ($finalProject->supervisor_1_id) {
                $toUserIds[] = (int) $finalProject->supervisor_1_id;
            }
            if ($finalProject->supervisor_2_id) {
                $toUserIds[] = (int) $finalProject->supervisor_2_id;
            }

            $studentName = $student?->nama_lengkap ?: 'Mahasiswa';
            NotificationHelper::notifyUsers(
                $toUserIds,
                'sidang.submitted',
                'Pengajuan Sidang Baru',
                "{$studentName} mengajukan pendaftaran Sidang. Silakan lakukan review/approval.",
                route('admin.final-project.defenses.index'),
                ['final_project_id' => $finalProject->id, 'defense_id' => $defense->id, 'program_studi' => $prodi]
            );

            return redirect()->route('student.final-project.index')
                ->with('success', 'Pendaftaran sidang TA berhasil disubmit. Menunggu persetujuan pembimbing.');

        } catch (\Exception $e) {
            \Log::error('Defense registration failed: '.$e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mendaftar sidang.');
        }
    }

    public function show($id)
    {
        $studentId = decrypt(session('student_id'));
        $defense = FinalProjectDefense::whereHas('finalProject', function($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })->with(['finalProject', 'approver'])->findOrFail($id);

        return view('students.final-project.defense.show', compact('defense'));
    }
}
