<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentsAdminController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::withCount('counselings')
            ->byLecturer(Auth::id())
            ->search($request->input('search'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.students.index', [
            'students' => $students,
            'search'   => $request->input('search'),
        ]);
    }

    public function showCardByLecture($id)
    {
        $student = Student::with('dosenPA')->findOrFail($id);

        $history = $student->counselings()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($counseling) {
                $ids = $counseling->failed_courses ?? [];
                $counseling->failed_courses_objects = Course::whereIn('id', $ids)->get();
                return $counseling;
            });

        return view('admin.counseling.add_form_student', compact('student', 'history'));
    }

    public function checkStudentByLecturer($id)
    {
        $dosen = User::findOrFail($id);

        if (!in_array($dosen->role, ['admin', 'superadmin', 'masteradmin'])) {
            return redirect()->back()->with('error', 'Dosen tidak ditemukan atau bukan dosen pembimbing.');
        }

        $angkatan = Student::byLecturer($id)
            ->select('angkatan', DB::raw('count(*) as total'))
            ->groupBy('angkatan')
            ->orderBy('angkatan')
            ->get();

        return view('admin.counseling.index_master', compact('angkatan', 'dosen'));
    }

    public function getStudentsByBatchLecturer(Request $request, $batch, $id)
    {
        $dosen = User::findOrFail($id);

        $students = Student::withCount('counselings')
            ->byLecturer($id)
            ->byBatch($batch)
            ->search($request->input('search'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.students.index_master', compact('students', 'dosen', 'batch'));
    }

    public function create()
    {
        return view('admin.students.create', [
            'menu' => 'Add Students',
        ]);
    }

    public function store(Request $request)
    {
        $student = Student::create(array_merge(
            $request->validated(),
            [
                'id_lecturer'      => Auth::id(),
                'program_studi'    => Student::DEFAULT_PROGRAM,
                'status_mahasiswa' => Student::STATUS_ACTIVE,
                'tanggal_masuk'    => now(),
            ]
        ));

        return redirect()->route('admin.students.index')
            ->with('success', "Mahasiswa {$student->nama_lengkap} berhasil ditambahkan.");
    }

    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', [
            'student' => $student,
            'menu'    => "Edit {$student->nama_lengkap}",
        ]);
    }

    public function update(\App\Http\Requests\UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->validated() + [
            'status_mahasiswa' => Student::STATUS_ACTIVE,
        ]);

        return redirect()->back()->with('success', 'Data mahasiswa berhasil diperbarui!');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Mahasiswa berhasil dihapus!');
    }
}