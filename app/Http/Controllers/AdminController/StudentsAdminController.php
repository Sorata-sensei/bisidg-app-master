<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\CardCounseling;
use App\Models\Course;
class StudentsAdminController extends Controller
{
    /**
     * Display a listing of the admin.students.
     */
   public function index(Request $request)
{
    $search = $request->input('search');

    $students = Student::withCount('counselings') // hitung jumlah counseling
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


    public function showCardByLecture($student_id)
{
    // Ambil data mahasiswa + dosen PA + counseling (riwayat)
    $student = Student::with(['dosenPA', 'counselings'])->findOrFail($student_id);

    // Ambil semua riwayat counseling, urutkan biar rapih
   $history = $student->counselings()->orderBy('created_at', 'asc')->get()->map(function ($item) {
        $ids = is_array($item->failed_courses)
            ? $item->failed_courses
            : json_decode($item->failed_courses, true);

        $item->failed_courses_objects = Course::whereIn('id', $ids ?: [])->get();
        return $item;
    });

    // Tampilkan form tambah counseling + riwayat
    return view('admin.counseling.add_form_student', compact('student', 'history'));
}

    public function CheckStudentByLecturer($id)
    {
       
        $dosen = User::find($id);
            if (!$dosen || !in_array($dosen->role, ['admin', 'superadmin', 'masteradmin'])) {
                return redirect()->back()->with('error', 'Dosen tidak ditemukan atau bukan dosen pembimbing.');
            }
        $angkatan = Student::query()
        ->where('id_lecturer', $id)
        ->select('angkatan', DB::raw('count(*) as total'))
        ->groupBy('angkatan')
        ->orderBy('angkatan', 'asc')
        ->get();

        return view('admin.counseling.index_master', compact('angkatan', 'dosen'));
    }

   public function getStudentsByBatchLecturer(Request $request, $batch, $id)
{
    $dosen = User::findOrFail($id);
    $search = $request->input('search');

    $students = Student::with('counselings') // ambil semua riwayat counseling
        ->where('id_lecturer', $id)
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

    
    public function create()
    {
        $menu = ' add students';
        return view('admin.students.create', compact('menu'));
    }

   public function store(Request $request)
{
    // Validasi (Sesuaikan field dengan nama di Blade)
    $validator = Validator::make($request->all(), [
        'full_name' => 'required|string|max:100',                      // → nama_lengkap
        'nim'       => 'required|string|unique:students,nim|max:12',  // → nim
        'batch'     => 'required|integer|min:1900|max:2100',          // → angkatan
        'gender'    => 'nullable|in:L,P',
        'address'   => 'nullable|string|max:500',                      // → program_studi
        'notes'     => 'nullable|string|max:1000',                    // → notes
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    
    $studentData = $request->only([
        'full_name', 
        'nim', 
        'batch', 
        'major', 
        'email', 
        'phone', 
        'notes',
        'gender',
        'address'
    ]);

  
     $studentData = [
        'id_lecturer'       => auth()->user()->id, 
        'nama_lengkap'      => $studentData['full_name'],
        'nim'               => $studentData['nim'],
        'angkatan'          => $studentData['batch'],
        'program_studi'     => 'Bisnis Digital', // default
        'email'             => $studentData['email'],
        'no_telepon'        => $studentData['phone'],
        'notes'             => $studentData['notes'],
        'jenis_kelamin'     => $studentData['gender'],
        'alamat'            => $studentData['address'],
        'status_mahasiswa'  => 'Aktif', // default
        'tanggal_masuk'     => now(),   // set tanggal masuk sesuai saat ini
    ];

    // ✅ Tambahkan field yang perlu diisi (yang tidak ada di form, tapi wajib)
    // Jika kamu ingin tambahkan field lain (seperti jenis_kelamin, tanggal_lahir, alamat) -> masukin manual
    // Contoh (opsional): 
    // 'jenis_kelamin' => 'L',  // atau dari form jika sudah ditambahkan
    // 'tanggal_lahir' => '1990-01-01',
    // 'alamat'        => 'Jl. Contoh No. 123',

    // 🚀 Simpan data ke database
    Student::create($studentData);

    return redirect()->route('admin.students.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
}


    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit ($id)
    {
        $student = Student::where('id',$id)->first();
        $menu  = 'Edit '. $student->nama_lengkap;
        
        return view('admin.students.edit', compact('student','menu'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, $id)
    {
          // Validasi input
    $validator = Validator::make($request->all(), [
        'nama_lengkap'   => 'required|string|max:100',
        'nim'            => 'required|string|max:12|unique:students,nim,' . $id,
        'angkatan'       => 'required|integer|min:1900|max:2100',
        'program_studi'  => 'required|string|max:50',
        'fakultas'       => 'nullable|string|max:100',
        'jenis_kelamin'  => 'required|in:L,P',
        'alamat'         => 'required|string|max:500',
        'email'          => 'nullable|email|max:100|unique:students,email,' . $id,
        'no_telepon'     => 'nullable|string|max:15',
        'notes'          => 'nullable|string|max:1000',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $studentData = $validator->validated();

    // Tambahkan default jika perlu
    $studentData['status_mahasiswa'] = 'Aktif';
    $studentData['tanggal_masuk']    = now();

    // Update ke database dengan where id
    Student::where('id', $id)->update($studentData);

    return redirect()->back()
        ->with('success', 'Data mahasiswa berhasil diperbarui!');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy($id)
{
    $student = Student::findOrFail($id); 
    $student->delete();

    return redirect()->route('admin.students.index')
        ->with('success', 'Mahasiswa berhasil dihapus!');
}

}