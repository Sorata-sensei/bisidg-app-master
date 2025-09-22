<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\CardCounseling;
use Illuminate\Http\Request;
use App\Models\Course;
    use Illuminate\Support\Facades\DB;
class CardCounselingController extends Controller
{
    public function show()
{
    $courses = Course::all();
    $student = Student::with('dosenPA')->findOrFail(decrypt(session('student_id')));

    if ($student->id !== decrypt(session('student_id'))) {
        return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }

    if ($student->ttd == null || $student->foto == null) {
        return redirect()->route('student.personal.editDataIndex', encrypt(session('student_id')))
            ->with('error', 'Silakan lengkapi data pribadi Anda (foto dan tanda tangan) sebelum mengakses layanan konsultasi akademik.');
    }

  $history = $student->counselings()
    ->orderBy('created_at','desc')
    ->take(1)
    ->get()
    ->map(function ($item) {
        $ids = is_array($item->failed_courses)
            ? $item->failed_courses
            : json_decode($item->failed_courses, true);

        $item->failed_courses_objects = Course::whereIn('id', $ids ?: [])->get();
        return $item;
    });

    // mapping failed_courses → course objects
    // foreach ($history as $row) {
    //     if (!empty($row->failed_courses)) {
    //         $row->failed_courses_objects = Course::whereIn('id', $row->failed_courses)->get();
    //     } else {
    //         $row->failed_courses_objects = collect();
    //     }
    // }

    return view('students.counseling.add_form_student', compact('student', 'history', 'courses'));
}


    



public function store(Request $request, $id_student)
{
    $student = Student::findOrFail(decrypt(session('student_id')));

    $validated = $request->validate([
        'semester'          => 'required|numeric|min:1',
        'sks'               => 'required|numeric|min:1',
        'ip'                => [
            'nullable',
            'regex:/^(?:\d{1})(\.\d{1,2})?$/'
        ],
        'tanggal'           => 'required|date',
        'komentar'          => 'nullable|string|max:500',
        'failed_courses'    => 'nullable|array',
        'failed_courses.*'  => 'string|max:100',
        'retaken_courses'   => 'nullable|array',
        'retaken_courses.*' => 'string|max:100',
    ], [
        'ip.regex' => 'IP harus dalam format x.xx (contoh: 3.43, maksimal 2 digit sebelum dan 2 digit setelah koma).',
    ]);

    DB::transaction(function () use ($student, $validated, $id_student) {
        CardCounseling::create([
            'id_student'      => $id_student,
            'semester'        => $validated['semester'],
            'sks'             => $validated['sks'],
            'ip'              => $validated['ip'] ?? null,
            'tanggal'         => $validated['tanggal'],
            'komentar'        => $validated['komentar'] ?? null,
            'failed_courses'  => $validated['failed_courses'] ?? [],
            'retaken_courses' => $validated['retaken_courses'] ?? [],
        ]);

        // hanya jalan kalau create berhasil
        $student->is_counseling = '0'; 
        $student->save();
    });

    return redirect()
        ->route('student.counseling.show', encrypt(session('student_id')))
        ->with('success', 'Data konsultasi berhasil ditambahkan');
}




}