<?php

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
| 3. Rute API Data JSON Peta
|--------------------------------------------------------------------------
*/
// Mengambil data spasial utama untuk peta (membaca dari method apiPeta)
Route::get('/api/sekolah', [SekolahController::class, 'apiPeta'])->name('sekolah.api');


/*
|--------------------------------------------------------------------------
| 4. Rute Otentikasi Bawaan Laravel
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
