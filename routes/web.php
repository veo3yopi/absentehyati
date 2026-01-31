<?php

use App\Http\Controllers\TeacherAuthController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Middleware\EnsureTeacher;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('guru')->group(function () {
    Route::get('/login', [TeacherAuthController::class, 'showLogin'])->name('guru.login');
    Route::post('/login', [TeacherAuthController::class, 'login'])->name('guru.login.submit');
    Route::post('/logout', [TeacherAuthController::class, 'logout'])->name('guru.logout');

    Route::middleware([EnsureTeacher::class])->group(function () {
        Route::get('/', [TeacherDashboardController::class, 'index'])->name('guru.dashboard');
        Route::post('/absen-masuk', [TeacherDashboardController::class, 'storeCheckIn'])->name('guru.absen.checkin');
        Route::post('/absen-pulang', [TeacherDashboardController::class, 'storeCheckOut'])->name('guru.absen.checkout');
    });
});
