<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceScannerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\RecapController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SchoolSettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Informasi Absensi Siswa
|--------------------------------------------------------------------------
*/

// Halaman Utama: Langsung masuk ke aplikasi absensi siswa
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

// Portal Publik: Cek Mandiri Kehadiran Siswa (Untuk Siswa & Orang Tua)
Route::get('/cek-presensi', [StudentController::class, 'publicCheck'])->name('public.check');

// Autentikasi Pengguna
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Alur Pemulihan Kata Sandi (Lupa Password)
    Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Halaman Internal yang Memerlukan Login (FR-04)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Modul Pencatatan & Riwayat Absensi (Bisa diakses Admin & Petugas)
    Route::get('/attendance/scanner', [AttendanceScannerController::class, 'index'])->name('attendance.scanner');
    Route::post('/attendance/scanner/scan', [AttendanceScannerController::class, 'scan'])->name('attendance.scanner.scan');
    Route::get('/attendance/{attendance}/print', [AttendanceController::class, 'print'])->name('attendance.print');
    Route::get('/attendance/{attendance}/export-excel', [AttendanceController::class, 'exportExcel'])->name('attendance.export-excel');
    Route::post('/attendance/{attendance}/toggle-compensation', [AttendanceController::class, 'toggleCompensation'])->name('attendance.toggle-compensation');
    Route::resource('attendance', AttendanceController::class);

    // Modul Rekapitulasi (FR-24 s.d FR-32)
    Route::get('/recap/student', [RecapController::class, 'studentRecap'])->name('recap.student');
    Route::get('/recap/class', [RecapController::class, 'classRecap'])->name('recap.class');
    Route::get('/recap/class/export-excel', [RecapController::class, 'exportClassExcel'])->name('recap.class.export-excel');

    // Modul Laporan & Cetak (FR-33 s.d FR-38)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/print', [ReportController::class, 'print'])->name('reports.print');
    Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');
    Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.export');

    // Lihat Data Siswa & Kelas (Admin & Petugas)
    Route::get('/students/cards', [StudentController::class, 'cards'])->name('students.cards');
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/classrooms', [ClassroomController::class, 'index'])->name('classrooms.index');
    Route::get('/classrooms/{classroom}', [ClassroomController::class, 'show'])->name('classrooms.show');

    // Khusus Administrator (Kelola Master Data & Akun)
    Route::middleware(['role:admin'])->group(function () {
        // CRUD Siswa & Batch
        Route::get('/students-batch', [StudentController::class, 'batchCreate'])->name('students.batch-create');
        Route::post('/students-batch', [StudentController::class, 'batchStore'])->name('students.batch-store');
        Route::get('/students-create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

        // CRUD Rombel
        Route::post('/classrooms', [ClassroomController::class, 'store'])->name('classrooms.store');
        Route::put('/classrooms/{classroom}', [ClassroomController::class, 'update'])->name('classrooms.update');
        Route::delete('/classrooms/{classroom}', [ClassroomController::class, 'destroy'])->name('classrooms.destroy');

        // Tahun Ajaran
        Route::get('/academic-years', [AcademicYearController::class, 'index'])->name('academic-years.index');
        Route::post('/academic-years', [AcademicYearController::class, 'store'])->name('academic-years.store');
        Route::put('/academic-years/{academicYear}', [AcademicYearController::class, 'update'])->name('academic-years.update');
        Route::post('/academic-years/{academicYear}/toggle', [AcademicYearController::class, 'toggleActive'])->name('academic-years.toggle');
        Route::delete('/academic-years/{academicYear}', [AcademicYearController::class, 'destroy'])->name('academic-years.destroy');

        // Manajemen Pengguna
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Pengaturan Identitas Sekolah & Kop Surat
        Route::get('/settings/backup', [SchoolSettingController::class, 'downloadBackup'])->name('settings.backup');
        Route::get('/settings', [SchoolSettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SchoolSettingController::class, 'update'])->name('settings.update');
    });
});
