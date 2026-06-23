<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Require2FA
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Check if user is authenticated and is an admin or pegawai
        if ($user && in_array($user->role, ['admin', 'pegawai'])) {
            // Check if 2FA is NOT enabled
            if (empty($user->google2fa_secret) && !session('2fa_passed')) {
                // If the user is trying to access anything other than the 2fa setup or logout
                if (!$request->routeIs('2fa.*') && !$request->routeIs('logout')) {
                    // Redirect directly to the 2FA setup page
                    return redirect()->route('2fa.index')->with('error', 'Keamanan Akun: Anda DIWAJIBKAN untuk mengaktifkan Autentikasi 2 Langkah (2FA) sebelum menggunakan aplikasi.');
                }
            }
        }

        return $next($request);
    }
}
