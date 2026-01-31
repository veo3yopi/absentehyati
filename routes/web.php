<?php

use App\Http\Controllers\TeacherAuthController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Middleware\EnsureTeacher;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/guru');

Route::prefix('guru')->group(function () {
    Route::get('/login', [TeacherAuthController::class, 'showLogin'])->name('guru.login');
    Route::post('/login', [TeacherAuthController::class, 'login'])->name('guru.login.submit');
    Route::post('/logout', [TeacherAuthController::class, 'logout'])->name('guru.logout');

    Route::middleware([EnsureTeacher::class])->group(function () {
        Route::get('/', [TeacherDashboardController::class, 'index'])->name('guru.dashboard');
        Route::get('/absen', [TeacherDashboardController::class, 'attendancePage'])->name('guru.absen.page');
        Route::get('/pengajuan', [TeacherDashboardController::class, 'requestPage'])->name('guru.absen.request.page');
        Route::get('/riwayat', [TeacherDashboardController::class, 'historyPage'])->name('guru.absen.history');
        Route::post('/absen-masuk', [TeacherDashboardController::class, 'storeCheckIn'])->name('guru.absen.checkin');
        Route::post('/absen-pulang', [TeacherDashboardController::class, 'storeCheckOut'])->name('guru.absen.checkout');
        Route::post('/pengajuan-absen', [TeacherDashboardController::class, 'storeAbsenceRequest'])->name('guru.absen.request');
    });
});
