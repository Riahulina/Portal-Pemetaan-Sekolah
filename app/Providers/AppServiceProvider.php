<?php

namespace App\Providers;

use App\Models\LaporanKoreksi;
use App\Models\SekolahTemporary;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin', function ($user) {
            return $user->is_admin === true;
        });

        RateLimiter::for('public_api', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });

        View::composer('partials.adminSidebar', function ($view) {
            $view->with('pendingKoreksi', LaporanKoreksi::where('status', 'pending')->count());
            $view->with('pendingPendaftaran', SekolahTemporary::where('status_verifikasi', 'pending')->count());
        });

        Event::listen(TransactionBeginning::class, function () {
            if (DB::connection()->getDriverName() === 'pgsql') {
                $userId = Auth::check() ? (string) Auth::id() : '0';
                $isAdmin = (Auth::check() && Auth::user()->is_admin) ? 'true' : 'false';
                DB::statement("SELECT set_config('app.user_id', ?::text, false)", [$userId]);
                DB::statement("SELECT set_config('app.is_admin', ?, false)", [$isAdmin]);
            }
        });

        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            return (new MailMessage)
                ->subject('Notifikasi Reset Password - SatuPeta')
                ->greeting('Halo!')
                ->line('Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.')
                ->action('Reset Password', url(route('password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false)))
                ->line('Link reset password ini akan kedaluwarsa dalam '.config('auth.passwords.'.config('auth.defaults.passwords').'.expire').' menit.')
                ->line('Jika Anda tidak meminta reset password, tidak ada tindakan lebih lanjut yang diperlukan.')
                ->salutation('Salam, SatuPeta');
        });
    }
}
