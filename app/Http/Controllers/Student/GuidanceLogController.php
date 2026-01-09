<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\FinalProject;
use App\Models\FinalProjectGuidanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuidanceLogController extends Controller
{
    public function index()
    {
        $studentId = decrypt(session('student_id'));
        $finalProject = FinalProject::where('student_id', $studentId)->firstOrFail();
        
        $logs = FinalProjectGuidanceLog::where('final_project_id', $finalProject->id)
            ->with('supervisor')
            ->orderBy('guidance_date', 'desc')
            ->paginate(15);

        return view('students.final-project.guidance.index', compact('logs', 'finalProject'));
    }

    public function create()
    {
        $studentId = decrypt(session('student_id'));
        $finalProject = FinalProject::with(['supervisor1', 'supervisor2'])->where('student_id', $studentId)->firstOrFail();
        
        return view('students.final-project.guidance.create', compact('finalProject'));
    }

    public function store(Request $request)
    {
        $studentId = decrypt(session('student_id'));
        $finalProject = FinalProject::where('student_id', $studentId)->firstOrFail();

        $request->validate([
            'supervisor_id' => 'required|exists:users,id',
            'guidance_date' => 'required|date',
            'materials_discussed' => 'required|string',
            'student_notes' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        try {
            $data = $request->only(['supervisor_id', 'guidance_date', 'materials_discussed', 'student_notes']);
            $data['final_project_id'] = $finalProject->id;
            $data['status'] = 'pending';

            if ($request->hasFile('file')) {
                $data['file_path'] = $request->file('file')->store("final-projects/{$studentId}/guidance", 'public');
            }

            FinalProjectGuidanceLog::create($data);

            return redirect()->route('student.guidance.index')
                ->with('success', 'Log bimbingan berhasil ditambahkan. Menunggu persetujuan dosen.');

        } catch (\Exception $e) {
            \Log::error('Guidance log creation failed: '.$e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menambahkan log bimbingan.');
        }
    }

    public function edit($id)
    {
        $studentId = decrypt(session('student_id'));
        $log = FinalProjectGuidanceLog::whereHas('finalProject', function($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })->where('status', 'pending')->findOrFail($id);

        $finalProject = $log->finalProject()->with(['supervisor1', 'supervisor2'])->first();

        return view('students.final-project.guidance.edit', compact('log', 'finalProject'));
    }

    public function update(Request $request, $id)
    {
        $studentId = decrypt(session('student_id'));
        $log = FinalProjectGuidanceLog::whereHas('finalProject', function($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })->where('status', 'pending')->findOrFail($id);

        $request->validate([
            'supervisor_id' => 'required|exists:users,id',
            'guidance_date' => 'required|date',
            'materials_discussed' => 'required|string',
            'student_notes' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        try {
            $log->fill($request->only(['supervisor_id', 'guidance_date', 'materials_discussed', 'student_notes']));

            if ($request->hasFile('file')) {
                if ($log->file_path) {
                    Storage::disk('public')->delete($log->file_path);
                }
                $log->file_path = $request->file('file')->store("final-projects/{$studentId}/guidance", 'public');
            }

            $log->save();

            return redirect()->route('student.guidance.index')
                ->with('success', 'Log bimbingan berhasil diperbarui.');

        } catch (\Exception $e) {
            \Log::error('Guidance log update failed: '.$e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui log bimbingan.');
        }
    }

    public function destroy($id)
    {
        $studentId = decrypt(session('student_id'));
        $log = FinalProjectGuidanceLog::whereHas('finalProject', function($q) use ($studentId) {
            $q->where('student_id', $studentId);
        })->where('status', 'pending')->findOrFail($id);

        try {
            if ($log->file_path) {
                Storage::disk('public')->delete($log->file_path);
            }
            $log->delete();

            return redirect()->route('student.guidance.index')
                ->with('success', 'Log bimbingan berhasil dihapus.');

        } catch (\Exception $e) {
            \Log::error('Guidance log deletion failed: '.$e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus log bimbingan.');
        }
    }
}
