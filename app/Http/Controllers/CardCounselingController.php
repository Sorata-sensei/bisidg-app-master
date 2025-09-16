<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\CardCounseling;
use Illuminate\Http\Request;
use App\Models\Course;
class CardCounselingController extends Controller
{
    public function show($id_student)
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

    $request->validate([
        'semester'          => 'required|numeric',
        'sks'               => 'required|numeric',
        'ip'                => 'nullable|numeric|between:0,999.99',
        'tanggal'           => 'required|date',
        'komentar'          => 'nullable|string',
        'failed_courses'    => 'nullable|array',   // validasi harus array
        'failed_courses.*'  => 'string',           // setiap item berupa string
        'retaken_courses'   => 'nullable|array',   // validasi juga array
        'retaken_courses.*' => 'string',           // tiap item string juga
    ]);

    $student->is_counseling = '0'; 
    $student->save();

    CardCounseling::create([
        'id_student'      => $id_student,
        'semester'        => $request->semester,
        'sks'             => $request->sks,
        'ip'              => $request->ip,
        'tanggal'         => $request->tanggal,
        'komentar'        => $request->komentar,
        'failed_courses'  => $request->failed_courses,   // langsung array
        'retaken_courses' => $request->retaken_courses,  // langsung array
    ]);


    return redirect()
        ->route('student.counseling.show', encrypt(session('student_id')))
        ->with('success', 'Data konsultasi berhasil ditambahkan');
}



}