<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController\DashboardController;
use App\Http\Controllers\AdminController\UserManageController;
use App\Http\Controllers\AdminController\InternshipController;
use App\Http\Controllers\AdminController\StudentsAdminController;
use App\Http\Controllers\CounselingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:superadmin,masteradmin'])->group(function () {



     Route::prefix('admin/students')
    ->name('admin.students.')
    ->group(function () {
       Route::get('/get-students-lecture/{batch}/{id}', [StudentsAdminController::class, 'getStudentsByBatchLecturer'])
                ->name('getStudentsByBatchLecturer');
        Route::get('/CheckStudentByLecturer/{id}', [StudentsAdminController::class, 'CheckStudentByLecturer'])->name('CheckStudentByLecturer');
        Route::get('/showCardByLecture/{id}',[StudentsAdminController::class, 'showCardByLecture'])->name('showCardByLecture');
    });

      Route::controller(UserManageController::class)
        ->prefix('admin/user')
        ->name('user.admin.')
        ->group(function () {
           
            Route::get('/main', 'indexMain')->name('main');
            Route::get('/create', 'create')->name('create');
            
        });
});


Route::middleware(['auth', 'role:admin,superadmin,masteradmin'])->group(function () {

    // Counseling
    Route::prefix('admin/counseling')
        ->name('admin.counseling.')
        ->group(function () {
            Route::get('/', [CounselingController::class, 'index'])->name('index');
            Route::get('/get-students/{batch}', [CounselingController::class, 'getStudentsByBatch'])
                ->name('getStudentsByBatch');
           
            Route::get('/open-close/{id}', [CounselingController::class, 'openclose'])
                ->name('openclose');
        });

    // Internship
    Route::prefix('admin/internship')
        ->name('admin.internship.')
        ->group(function () {
            Route::get('/', [InternshipController::class, 'index'])->name('index');
            Route::get('/create', [InternshipController::class, 'create'])->name('create');
            Route::post('/', [InternshipController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [InternshipController::class, 'edit'])->name('edit');
            Route::put('/{id}', [InternshipController::class, 'update'])->name('update');
            Route::delete('/{id}', [InternshipController::class, 'destroy'])->name('destroy');
        });

    // Students
   Route::prefix('admin/students')
    ->name('admin.students.')
    ->group(function () {
        Route::get('/', [StudentsAdminController::class, 'index'])->name('index');
        Route::get('/create', [StudentsAdminController::class, 'create'])->name('create');
        Route::get('/CheckStudentByLecturer/{id}', [StudentsAdminController::class, 'CheckStudentByLecturer'])->name('CheckStudentByLecturer');
        Route::post('/', [StudentsAdminController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [StudentsAdminController::class, 'edit'])->name('edit');
        Route::put('/{id}', [StudentsAdminController::class, 'update'])->name('update');
        Route::delete('/{id}', [StudentsAdminController::class, 'destroy'])->name('destroy');
        Route::get('/showCardByLecture/{id}',[StudentsAdminController::class, 'showCardByLecture'])->name('showCardByLecture');
    });
    // Dashboard
    Route::controller(DashboardController::class)
        ->prefix('admin/dashboard')
        ->group(function () {
            Route::get('/', 'index')->name('dashboard.admin.index');
        });

    // User Management
    Route::controller(UserManageController::class)
        ->prefix('admin/user')
        ->name('user.admin.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
          
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

});