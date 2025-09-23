<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\CardCounseling;
use App\Models\Course;

class StudentsAdminController extends Controller
{
    /**
     * Display a listing of students by lecturer.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $students = Student::withCount('counselings')
            ->where('id_lecturer', Auth::id())
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%")
                        ->orWhere('angkatan', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('admin.students.index', compact('students', 'search'));
    }

    /**
     * Show student counseling card by lecturer.
     */
    public function showCardByLecture($student_id)
    {
        $student = Student::with(['dosenPA', 'counselings'])->findOrFail($student_id);

        $history = $student->counselings()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($item) {
                $ids = is_array($item->failed_courses)
                    ? $item->failed_courses
                    : json_decode($item->failed_courses, true);

                $item->failed_courses_objects = Course::whereIn('id', $ids ?: [])->get();
                return $item;
            });

        return view('admin.counseling.add_form_student', compact('student', 'history'));
    }

    /**
     * Check students grouped by batch under lecturer.
     */
    public function CheckStudentByLecturer($id)
    {
        $dosen = User::find($id);

        if (!$dosen || !in_array($dosen->role, ['admin', 'superadmin', 'masteradmin'])) {
            return redirect()->back()->with('error', 'Dosen tidak ditemukan atau bukan dosen pembimbing.');
        }

        $angkatan = Student::where('id_lecturer', $id)
            ->select('angkatan', DB::raw('count(*) as total'))
            ->groupBy('angkatan')
            ->orderBy('angkatan', 'asc')
            ->get();

        return view('admin.counseling.index_master', compact('angkatan', 'dosen'));
    }

    /**
     * Get students by batch and lecturer.
     */
    public function getStudentsByBatchLecturer(Request $request, $batch, $id)
    {
        $dosen = User::findOrFail($id);
        $search = $request->input('search');

        $students = Student::withCount('counselings')
            ->where('id_lecturer', Auth::id())
            ->where('angkatan', $batch)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%")
                        ->orWhere('angkatan', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('admin.students.index_master', compact('students', 'dosen', 'batch'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $menu = 'Add Student';
        return view('admin.students.create', compact('menu'));
    }

    /**
     * Store new student.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:100',
            'nim'       => 'required|string|unique:students,nim|max:12',
            'batch'     => 'required|integer|min:1900|max:2100',
            'gender'    => 'nullable|in:L,P',
            'address'   => 'nullable|string|max:500',
            'notes'     => 'nullable|string|max:1000',
            'email'     => 'nullable|email|max:100|unique:students,email',
            'phone'     => 'nullable|string|max:15',
        ], [
            'full_name.required' => 'Full name cannot be empty.',
            'nim.required'       => 'NIM cannot be empty.',
            'nim.unique'         => 'This NIM is already registered, please use another one.',
            'batch.required'     => 'Batch cannot be empty.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $studentData = [
            'id_lecturer'      => auth()->id(),
            'nama_lengkap'     => $request->full_name,
            'nim'              => $request->nim,
            'angkatan'         => $request->batch,
            'program_studi'    => 'Bisnis Digital',
            'email'            => $request->email,
            'no_telepon'       => $request->phone,
            'notes'            => $request->notes,
            'jenis_kelamin'    => $request->gender,
            'alamat'           => $request->address,
            'status_mahasiswa' => 'Aktif',
            'tanggal_masuk'    => now(),
        ];

        Student::create($studentData);

        return redirect()->route('admin.students.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    /**
     * Show a specific student.
     */
    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $menu = 'Edit ' . $student->nama_lengkap;

        return view('admin.students.edit', compact('student', 'menu'));
    }

    /**
     * Update a student.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:100',
            'nim'          => 'required|string|max:12|unique:students,nim,' . $id,
            'angkatan'     => 'required|integer|min:1900|max:2100',
            'program_studi'=> 'required|string|max:50',
            'fakultas'     => 'nullable|string|max:100',
            'jenis_kelamin'=> 'required|in:L,P',
            'alamat'       => 'required|string|max:500',
            'email'        => 'nullable|email|max:100|unique:students,email,' . $id,
            'no_telepon'   => 'nullable|string|max:15',
            'notes'        => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $studentData = $validator->validated();
        $studentData['status_mahasiswa'] = 'Aktif';

        Student::where('id', $id)->update($studentData);

        return redirect()->back()
            ->with('success', 'Data mahasiswa berhasil diperbarui!');
    }


    /**
     * Reset a student's password to default.
     */    
    public function resetpassword($id)
    {
        $student = Student::findOrFail($id);
        $student->password = bcrypt('Bisdig2025');
        $student->save();

        return redirect()->back()->with('success', "Password untuk {$student->nama_lengkap} telah direset ke 'Bsidig2025'.");
    }
    /**
     * Remove a student.
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Mahasiswa berhasil dihapus!');
    }
}