<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Counseling;
use App\Models\Student;
use Auth;
use DB;
class CounselingController extends Controller
{
   public function index() {
       
        
      $angkatan = Student::query()
    ->where('id_lecturer', Auth::id())
    ->select('angkatan', DB::raw('count(*) as total'))
    ->groupBy('angkatan')
    ->orderBy('angkatan', 'asc')
    ->get();



        return view('admin.counseling.index', compact('angkatan'));
    }
    public function getStudentsByBatch($batch, Request $request)
{
    $search = $request->input('search');

    $students = Student::withCount('counselings') // hitung counseling per mahasiswa
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

    return view('admin.students.index', compact('students', 'batch'));
}


    public function counselingadd() {
        

        $students = Student::where('is_counseling', 0)->get(); // Ambil mahasiswa yang belum konseling
        return view('students.counseling.add_form_student', compact('students'));
    }

    public function openclose($id){
        $counseling = Student::find($id);
        if ($counseling->is_counseling == 0) {
            $counseling->is_counseling = '1'; 
            $counseling->save();
            return redirect()->back()->with('success', 'Status counseling berhasil diubah.');
        
        }
        if ($counseling->is_counseling == 1) {
           $counseling->is_counseling = '0'; 
            $counseling->save();
            return redirect()->back()->with('success', 'Status counseling berhasil diubah.');
        }
    }
    public function opencloseedit($id){
        $counseling = Student::find($id);
        if ($counseling->is_edited == 0) {
            $counseling->is_edited = '1'; 
            $counseling->save();
            return redirect()->back()->with('success', 'Status Edit Data berhasil diubah.');
        
        }
        if ($counseling->is_edited == 1) {
           $counseling->is_edited = '0'; 
            $counseling->save();
            return redirect()->back()->with('success', 'Status Edit Data berhasil diubah.');
        }
    }
}