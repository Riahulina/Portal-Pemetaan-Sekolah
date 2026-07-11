<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;

class SekolahController extends Controller
{
    public function index()
    {
        $sekolah = Sekolah::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return response()->json($sekolah);
    }
}
