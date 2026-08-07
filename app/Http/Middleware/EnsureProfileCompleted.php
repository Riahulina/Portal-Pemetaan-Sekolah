<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileCompleted
{
    /**
     * User non-admin yang belum mengisi nomor telepon (mis. hasil registrasi
     * via Google) diarahkan ke halaman profil agar data mandatanya lengkap
     * sebelum bisa mengakses dashboard / form pengajuan sekolah.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->is_admin && is_null($user->phone_number)) {
            return redirect()->route('profile.user')
                ->with('warning', 'Silakan lengkapi profil dan nomor telepon Anda sebelum melanjutkan.');
        }

        return $next($request);
    }
}
