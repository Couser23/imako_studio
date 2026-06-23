<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        if (!$request->user()->hasVerifiedEmail() && $request->user()->role === 'user') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors([
                'email' => 'Anda harus melakukan verifikasi email terlebih dahulu. Silakan cek kotak masuk email Anda.',
            ]);
        }

        if ($request->user()->google2fa_secret) {
            $request->session()->put('2fa_pending_id', $request->user()->id);
            $request->session()->put('2fa_remember', $request->boolean('remember'));
            Auth::guard('web')->logout();
            return redirect()->route('2fa.challenge');
        }

        $request->session()->regenerate();

        // Log the login activity
        $userAgent = $request->userAgent();
        $browser = 'Browser';
        if (str_contains($userAgent, 'Chrome')) {
            $browser = 'Chrome';
        } elseif (str_contains($userAgent, 'Firefox')) {
            $browser = 'Firefox';
        } elseif (str_contains($userAgent, 'Safari')) {
            $browser = 'Safari';
        } elseif (str_contains($userAgent, 'Edge')) {
            $browser = 'Edge';
        }
        $os = str_contains($userAgent, 'Windows') ? 'Windows' : (str_contains($userAgent, 'Mac') ? 'macOS' : 'Linux');
        \App\Models\ActivityLog::log('login', 'Login berhasil', "{$browser} · {$os} · {$request->ip()}");

        $url = '/';
        if ($request->user()->role === 'admin') {
            $url = '/admin/dashboard';
        } elseif ($request->user()->role === 'pegawai') {
            $url = '/pegawai/dashboard';
        } elseif ($request->user()->role === 'user') {
            $url = '/user/dashboard';
        }

        return redirect()->intended($url);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
