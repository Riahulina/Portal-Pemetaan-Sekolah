<?php

use App\Http\Controllers\RefWilayahController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Semua rute API referensi wilayah (tanpa auth — data publik untuk
| kebutuhan filter dashboard). Rute di-group di bawah prefix /ref.
|--------------------------------------------------------------------------
*/

Route::middleware('throttle:public_api')->prefix('ref')->name('ref.')->group(function () {
    Route::get('/pulau', [RefWilayahController::class, 'pulau'])->name('pulau');
    Route::get('/provinsi', [RefWilayahController::class, 'provinsi'])->name('provinsi');
    Route::get('/kabupaten', [RefWilayahController::class, 'kabupaten'])->name('kabupaten');
    Route::get('/kecamatan', [RefWilayahController::class, 'kecamatan'])->name('kecamatan');
});
