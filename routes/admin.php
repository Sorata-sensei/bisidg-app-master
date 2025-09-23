<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController\DashboardController;
use App\Http\Controllers\AdminController\UserManageController;
use App\Http\Controllers\AdminController\InternshipController;
use App\Http\Controllers\AdminController\StudentsAdminController;
use App\Http\Controllers\AdminController\CounselingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Hanya Superadmin & Masteradmin
Route::middleware(['auth', 'role:superadmin,masteradmin'])->group(function () {

    // Students (khusus untuk akses tertentu super/master admin)
    Route::prefix('admin/students')
        ->name('admin.students.')
        ->group(function () {
            Route::get('/get-students-lecture/{batch}/{id}', [StudentsAdminController::class, 'getStudentsByBatchLecturer'])
                ->name('getStudentsByBatchLecturer');
            Route::get('/CheckStudentByLecturer/{id}', [StudentsAdminController::class, 'CheckStudentByLecturer'])
                ->name('CheckStudentByLecturer');
            Route::get('/showCardByLecture/{id}', [StudentsAdminController::class, 'showCardByLecture'])
                ->name('showCardByLecture');
        });

    // User Management (khusus super/master admin)
    Route::controller(UserManageController::class)
        ->prefix('admin/user')
        ->name('user.admin.')
        ->group(function () {
            Route::get('/main', 'indexMain')->name('main');
            Route::get('/create', 'create')->name('create');
        });
});


// Admin, Superadmin, Masteradmin
Route::middleware(['auth', 'role:admin,superadmin,masteradmin'])->group(function () {

    // Dashboard
    Route::controller(DashboardController::class)
        ->prefix('admin/dashboard')
        ->name('dashboard.admin.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
        });

    // Counseling
    Route::controller(CounselingController::class)
        ->prefix('admin/counseling')
        ->name('admin.counseling.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/get-students/{batch}', 'getStudentsByBatch')->name('getStudentsByBatch');
            Route::get('/open-close/{id}', 'openclose')->name('openclose');
            Route::get('/open-close/data/{id}', 'opencloseedit')->name('opencloseedit');
        });

    // Internship
    Route::controller(InternshipController::class)
        ->prefix('admin/internship')
        ->name('admin.internship.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

    // Students
    Route::controller(StudentsAdminController::class)
        ->prefix('admin/students')
        ->name('admin.students.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');

            // Tambahan spesifik
            Route::get('/CheckStudentByLecturer/{id}', 'CheckStudentByLecturer')->name('CheckStudentByLecturer');
            Route::get('/showCardByLecture/{id}', 'showCardByLecture')->name('showCardByLecture');
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