<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->with(['prompt' => 'select_account'])->redirect();
    }

    public function callback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            if (is_null($user->google_id)) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
            }
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => Hash::make(Str::random(24)),
            ]);
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        Auth::login($user, true);

        return redirect()->to(route('dashboard.user'));
    }
}
