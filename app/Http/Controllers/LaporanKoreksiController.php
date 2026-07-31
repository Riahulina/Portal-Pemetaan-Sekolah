<?php

namespace App\Http\Controllers;

use App\Models\LaporanKoreksi;
use Illuminate\Http\Request;

class LaporanKoreksiController extends Controller
{
    public function index()
    {
        $laporans = LaporanKoreksi::with('sekolah')
            ->where('email_pelapor', auth()->user()->email)
            ->latest()
            ->paginate(10);

        return view('User.riwayatUsulan', compact('laporans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sekolah_npsn' => 'required|string|max:10|exists:sekolah,npsn',
            'pesan_koreksi' => 'required|string|max:2000',
        ]);

        $validated['nama_pelapor'] = $request->user()->name;
        $validated['email_pelapor'] = $request->user()->email;
        $validated['user_id'] = $request->user()->id;
        $validated['status'] = 'pending';

        LaporanKoreksi::create($validated);

        return back()->with('success', 'Laporan berhasil dikirim dan akan segera ditinjau.');
    }
}
