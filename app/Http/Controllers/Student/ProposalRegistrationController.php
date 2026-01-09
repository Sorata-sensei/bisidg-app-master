<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Helpers\NotificationHelper;
use App\Models\FinalProject;
use App\Models\FinalProjectProposal;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProposalRegistrationController extends Controller
{
    public function create()
    {
        $studentId = decrypt(session('student_id'));
        $finalProject = FinalProject::where('student_id', $studentId)->firstOrFail();
        
        // Check if already has proposal
        if ($finalProject->proposal) {
            return redirect()->route('student.final-project.proposal.show', $finalProject->proposal->id)
                ->with('info', 'Anda sudah pernah mendaftar seminar proposal.');
        }

        // Validasi: Judul harus sudah approved
        if (!$finalProject->title_approved_at) {
            return redirect()->route('student.final-project.index')
                ->with('error', 'Judul Tugas Akhir harus disetujui terlebih dahulu sebelum mendaftar seminar proposal.');
        }

        // Validasi: Pembimbing 1 harus sudah ditentukan
        if (!$finalProject->supervisor_1_id) {
            return redirect()->route('student.final-project.index')
                ->with('error', 'Pembimbing 1 harus ditentukan oleh admin terlebih dahulu sebelum mendaftar seminar proposal.');
        }

        return view('students.final-project.proposal.create', compact('finalProject'));
    }

    public function store(Request $request)
    {
        $studentId = decrypt(session('student_id'));
        $finalProject = FinalProject::where('student_id', $studentId)->firstOrFail();

        $request->validate([
            // Jadwal seminar ditentukan oleh kaprodi saat approval (bukan oleh mahasiswa).
            'proposal_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'eligibility_form_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'guidance_form_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'seminar_approval_form_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'seminar_attendance_form_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'krs_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'transcript_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        try {
            // Create proposal
            $proposal = FinalProjectProposal::create([
                'final_project_id' => $finalProject->id,
                'registered_at' => now(),
                'scheduled_at' => null,
                'status' => 'pending',
            ]);

            // Upload files as documents
            $fileFields = [
                'proposal_file' => 'Proposal Tugas Akhir',
                'eligibility_form_file' => 'Form Penilaian Kelayakan Judul',
                'guidance_form_file' => 'Form Bimbingan Tugas Akhir',
                'seminar_approval_form_file' => 'Form Persetujuan Seminar Proposal',
                'seminar_attendance_form_file' => 'Form Mengikuti Seminar Proposal TA',
                'krs_file' => 'Kartu Rencana Studi Sem 1 - Sem Berjalan',
                'transcript_file' => 'Transkrip Nilai',
            ];

            foreach ($fileFields as $field => $title) {
                if ($request->hasFile($field)) {
                    $path = $request->file($field)->store("final-projects/{$studentId}/proposal", 'public');
                    
                    $finalProject->documents()->create([
                        'document_type' => 'proposal',
                        'title' => $title,
                        'file_path' => $path,
                        'version' => 1,
                        'uploaded_by' => $studentId,
                        'uploaded_at' => now(),
                    ]);
                }
            }

            // Notifikasi ke Kaprodi/Superuser (berdasarkan prodi) + pembimbing (jika ada)
            $student = Student::find($studentId);
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
                'sempro.submitted',
                'Pengajuan Sempro Baru',
                "{$studentName} mengajukan pendaftaran Sempro. Silakan lakukan review/approval.",
                route('admin.final-project.proposals.index'),
                ['final_project_id' => $finalProject->id, 'proposal_id' => $proposal->id, 'program_studi' => $prodi]
            );

            return redirect()->route('student.final-project.index')
                ->with('success', 'Pendaftaran seminar proposal berhasil disubmit. Menunggu persetujuan pembimbing.');

        } catch (\Exception $e) {
            \Log::error('Proposal registration failed: '.$e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mendaftar seminar proposal.');
        }
    }

    public function show($id)
    {
        $studentId = decrypt(session('student_id'));
        $proposal = FinalProjectProposal::whereHas('finalProject', function($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })->with(['finalProject.student', 'finalProject.supervisor1', 'finalProject.supervisor2', 'finalProject.documents', 'approver'])->findOrFail($id);

        return view('students.final-project.proposal.show', compact('proposal'));
    }
}
