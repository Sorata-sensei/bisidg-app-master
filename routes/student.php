<?php

use App\Http\Controllers\CardCounselingController;
use App\Http\Controllers\StudentsController;

Route::middleware(['student'])->group(function () {

    // Dashboard SuperApp
    Route::get('/student/dashboard', [StudentsController::class, 'dashboard'])->name('student.dashboard');

    // Counseling
    Route::controller(CardCounselingController::class)
        ->prefix('student/counseling')
        ->name('student.counseling.')
        ->group(function () {
            Route::get('/show', 'show')->name('show');
            Route::post('/{student}', 'store')->name('store');
        });

    // Personal Data
    Route::controller(StudentsController::class)
        ->prefix('student/personal')
        ->name('student.personal.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/edit', 'editDataIndex')->name('editDataIndex');
            Route::put('/update', 'updateData')->name('updateData');
            
            // Achievements
            Route::post('/achievement/store', 'storeAchievement')->name('achievement.store');
            Route::put('/achievement/update/{id}', 'updateAchievement')->name('achievement.update');
            Route::delete('/achievement/delete/{id}', 'deleteAchievement')->name('achievement.delete');
        });
});