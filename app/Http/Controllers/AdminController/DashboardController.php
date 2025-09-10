<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Student;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
       
        return view('admin/dashboard/index', [
           
            'menu'=>'Dashboard',
            'students' => Student::count(),
        ]);
    }
  
}