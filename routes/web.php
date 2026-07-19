<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSchoolController;
use App\Http\Controllers\Admin\AdminSekolahController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\SekolahController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. Halaman Utama (Landing Page)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
})->name('landing');

/*
|--------------------------------------------------------------------------
| 2. Rute Terproteksi Auth (User Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // --- DASHBOARD GROUP ---
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/user/dashboard', function () {
        if (auth()->user()->is_admin) {
            return redirect('/admin/dashboard');
        }

        return view('User.dashboardUser');
    })->name('dashboard.user');

    // --- MANAJEMEN DATA SEKOLAH USER ---
    // A. Halaman TABEL Daftar Sekolah Saya
    Route::get('/sekolah-saya', [SekolahController::class, 'index'])->name('sekolah.index');

    // B. Halaman FORMULIR Pendaftaran Sekolah
    Route::get('/user/Form', function () {
        return view('User.Form');
    })->name('Form.user');

    // C. PROSES SIMPAN Data Form ke Database (POST) -> Disinkronkan ke sekolah.store
    Route::post('/user/form/store', [SekolahController::class, 'store'])->name('sekolah.store');

    // D. Halaman STATUS STEPPER Verifikasi
    Route::get('/user/status-verifikasi', [SekolahController::class, 'statusVerifikasi'])->name('status.user');

    // E. Halaman Form Edit (Menampilkan data lama)
    Route::get('/sekolah/edit/{id}', [SekolahController::class, 'edit'])->name('sekolah.edit');

    // F. Proses Simpan Perubahan Data (PUT/PATCH)
    Route::put('/sekolah/update/{id}', [SekolahController::class, 'update'])->name('sekolah.update');

    // G. Proses Hapus Data Pengajuan (DELETE)
    Route::delete('/sekolah/hapus/{id}', [SekolahController::class, 'destroy'])->name('sekolah.destroy');
});

/*
|--------------------------------------------------------------------------
| 3. Rute Admin (Butuh Auth + Admin Middleware)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // --- MANAJEMEN SEKOLAH ---
    Route::get('/sekolah', [AdminSekolahController::class, 'index'])->name('sekolah.index');
    Route::put('/sekolah/{npsn}', [AdminSekolahController::class, 'update'])->name('sekolah.update');
    Route::delete('/sekolah/{npsn}', [AdminSekolahController::class, 'destroy'])->name('sekolah.destroy');

    Route::post('/sekolah/{id}/approve', [AdminSchoolController::class, 'approve'])
        ->name('sekolah.approve')
        ->middleware('throttle:10,1');

    Route::post('/sekolah/{id}/reject', [AdminSchoolController::class, 'reject'])
        ->name('sekolah.reject')
        ->middleware('throttle:10,1');

    // --- MANAJEMEN PENGGUNA ---
    Route::get('/pengguna', [AdminUserController::class, 'index'])->name('pengguna.index');
    Route::delete('/pengguna/{id}', [AdminUserController::class, 'destroy'])->name('pengguna.destroy');
});

/*
|--------------------------------------------------------------------------
| 4. Rute API Data JSON Peta
|--------------------------------------------------------------------------
*/
// Mengambil data wilayah unik untuk dropdown filter (cache permanen)
Route::get('/api/wilayah', [SekolahController::class, 'getWilayah'])->name('sekolah.wilayah');

// Mengambil ringkasan nasional per provinsi (cache permanen, lightweight)
Route::get('/api/sekolah/summary', [SekolahController::class, 'getProvinsiSummary'])->name('sekolah.summary');

// Mengambil data sekolah untuk peta — membutuhkan minimal ?provinsi=...
Route::get('/api/sekolah', [SekolahController::class, 'apiPeta'])->name('sekolah.api');

// Mengambil detail lengkap satu sekolah (on-demand, tanpa cache)
Route::get('/api/sekolah/{npsn}/detail', [SekolahController::class, 'getDetail'])->name('sekolah.detail');

/*
|--------------------------------------------------------------------------
| 5. Rute Otentikasi Bawaan Laravel
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
