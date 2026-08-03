<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetRlsContext
{
    /**
     * Bridge the Laravel-authenticated user into Postgres session variables
     * so that RLS policies can enforce row-level access.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            $user = $request->user();

            DB::statement("SELECT set_config('app.user_id', ?::text, false)", [$user ? (string) $user->id : '0']);
            DB::statement("SELECT set_config('app.is_admin', ?, false)", [$user?->is_admin ? 'true' : 'false']);
        }

        return $next($request);
    }
}
