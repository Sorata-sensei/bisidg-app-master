<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::controller(AuthController::class)->group(function () {  
        Route::get('/', 'index')->name('login');  
        Route::post('/login/auth-login', 'login')->name('auth.login');  
        Route::get('/logout', 'logout')->name('auth.logout');  
        Route::post('/auth/login/dosen', 'loginDosen')->name('auth.login.dosen');
        Route::post('/auth/login/mahasiswa', 'loginMahasiswa')->name('auth.login.mahasiswa');
    }); 