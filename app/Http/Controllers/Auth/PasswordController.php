<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Akun OAuth-only (google_id terisi, belum pernah menetapkan password)
        // boleh membuat password pertama kali tanpa memasukkan password lama.
        $rules = [
            'password' => ['required', Password::defaults(), 'confirmed'],
        ];

        if (is_null($user->google_id) || ! is_null($user->password_set_at)) {
            $rules['current_password'] = ['required', 'current_password'];
        }

        $validated = $request->validateWithBag('updatePassword', $rules);

        $user->update([
            'password' => Hash::make($validated['password']),
            'password_set_at' => now(),
        ]);

        return back()->with('status', 'password-updated');
    }
}
