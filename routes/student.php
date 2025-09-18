<?php
use App\Http\Controllers\CounselingController;
use App\Http\Controllers\CardCounselingController;
use App\Http\Controllers\StudentsController;


   




Route::middleware(['student'])->group(function () {  
    Route::prefix('student/counseling')->name('student.counseling.')->group(function () {
        Route::get('/show', [CardCounselingController::class, 'show'])->name('show');
        Route::post('/{student}', [CardCounselingController::class, 'store'])->name('store');
    });
    // personal
    Route::prefix('student/personal')->name('student.personal.')->group(function () {
        Route::get('/', [StudentsController::class, 'index'])->name('index');
        Route::get('/edit', [StudentsController::class, 'editDataIndex'])->name('editDataIndex');
        Route::put('/update', [StudentsController::class, 'updateData'])->name('updateData');
    });

    
});  