<?php

namespace App\Http\Controllers;

use App\Models\SekolahTemporary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DashboardUserController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // Jika yang login ternyata admin, tendang ke dashboard admin
        if ($user && $user->is_admin) {
            return redirect('/admin/dashboard');
        }

        $userId = Auth::id();
        $sekolahDaftar = SekolahTemporary::where('user_id', $userId)->get();

        // 1. Total Pendaftaran
        $totalPendaftaran = $sekolahDaftar->count();

        // 2. Hitung Menunggu Verifikasi (Mencari kata 'pending')
        $menungguVerifikasi = $sekolahDaftar->filter(function ($item) {
            return strtolower(trim($item->status_verifikasi)) === 'pending';
        })->count();

        // 3. Hitung Disetujui (Mencari kata 'approved' atau 'disetujui' agar aman)
        $disetujui = $sekolahDaftar->filter(function ($item) {
            $status = strtolower(trim($item->status_verifikasi));

            return $status === 'approved' || $status === 'disetujui';
        })->count();

        return view('User.dashboardUser', compact(
            'sekolahDaftar',
            'totalPendaftaran',
            'menungguVerifikasi',
            'disetujui'
        ));
    }

    // 1. Tampilkan Halaman Profile
    public function profile()
    {
        /** @var User $user */
        $user = Auth::user();

        return view('User.profile', compact('user'));
    }

    // 2. Proses Ubah Password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.current_password' => 'Password saat ini salah.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        /** @var User $user */
        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return back()->with('status', 'password-updated');
    }
}
