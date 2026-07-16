<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SekolahController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/api/sekolah', [SekolahController::class, 'index']);

require __DIR__.'/auth.php';
