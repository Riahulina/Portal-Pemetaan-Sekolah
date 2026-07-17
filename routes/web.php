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
});

/*
|--------------------------------------------------------------------------
| 2. Dashboard Default / Utama (File dashboard.blade.php)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| 3. Dashboard Khusus User (File User/dashboardUser.blade.php)
|--------------------------------------------------------------------------
*/
Route::get('/user/dashboard', function () {
    return view('User.dashboardUser');
})->middleware(['auth', 'verified'])->name('dashboard.user');


Route::get('/user/Form', function () {
    return view('User.Form');
})->middleware(['auth', 'verified'])->name('Form.user');

/*
|--------------------------------------------------------------------------
| 4. Rute API / Data Sekolah
|--------------------------------------------------------------------------
*/
Route::get('/api/sekolah', [SekolahController::class, 'index']);


// Rute Autentikasi bawaan Laravel (Login, Register, Logout, dll)
require __DIR__ . '/auth.php';
