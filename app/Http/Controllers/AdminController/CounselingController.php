<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class CounselingController extends Controller
{
    public function index()
    {
        $angkatan = Student::byLecturer(Auth::id())
            ->select('angkatan')
            ->selectRaw('count(*) as total')
            ->groupBy('angkatan')
            ->orderBy('angkatan', 'asc')
            ->get();

        return view('admin.counseling.index', compact('angkatan'));
    }

    public function getStudentsByBatch($batch, Request $request)
    {
        $students = Student::withCount('counselings')
            ->byLecturer(Auth::id())
            ->byBatch($batch)
            ->search($request->input('search'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.students.index', compact('students', 'batch'));
    }

    public function counselingAdd()
    {
        $students = Student::where('is_counseling', false)->get();
        return view('students.counseling.add_form_student', compact('students'));
    }

    public function toggleCounseling($id)
    {
        return $this->toggleStatus($id, 'is_counseling', 'Status counseling berhasil diubah.');
    }

    public function toggleEdit($id)
    {
        return $this->toggleStatus($id, 'is_edited', 'Status Edit Data berhasil diubah.');
    }

    /**
     * Helper untuk toggle boolean field di Student
     */
    protected function toggleStatus($id, $field, $message)
    {
        $student = Student::findOrFail($id);
        $student->$field = !$student->$field;
        $student->save();

        return redirect()->back()->with('success', $message);
    }
}