<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('admin.profile.index', compact('user'));
    }

    public function updateInfo(Request $request)
    {
        $request->validate([
            'phone_number' => ['nullable', 'string', 'starts_with:8', 'max:20'],
        ], [
            'phone_number.starts_with' => 'Nomor telepon harus diawali dengan angka 8 (tanpa angka 0 di depan).',
        ]);

        auth()->user()->update([
            'phone_number' => $request->input('phone_number'),
        ]);

        return back()->with('status', 'info-updated');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ], [
            'current_password.current_password' => 'Password saat ini salah.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return back()->with('status', 'password-updated');
    }
}
