<?php

namespace App\Http\Controllers;

use App\Models\LaporanKoreksi;

class AdminKoreksiController extends Controller
{
    public function index()
    {
        $laporans = LaporanKoreksi::with('sekolah')->latest()->paginate(15);

        return view('admin.koreksi.index', compact('laporans'));
    }

    public function resolve(LaporanKoreksi $laporan)
    {
        $laporan->update(['status' => 'selesai']);

        return back()->with('success', 'Laporan berhasil ditandai selesai.');
    }
}
