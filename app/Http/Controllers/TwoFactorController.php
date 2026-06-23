<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->google2fa_secret) {
            return redirect()->route('profile.edit')->with('status', '2fa-already-enabled');
        }

        $google2fa = new Google2FA();
        $secret = $request->session()->get('2fa_setup_secret');
        
        if (!$secret) {
            $secret = $google2fa->generateSecretKey();
            $request->session()->put('2fa_setup_secret', $secret);
        }

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(250),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $QR_Image = $writer->writeString($qrCodeUrl);

        return view('admin.profile.2fa', [
            'QR_Image' => $QR_Image,
            'secret' => $secret
        ]);
    }

    public function enable(Request $request)
    {
        $request->validate([
            'verify_code' => 'required|string',
        ]);

        $user = $request->user();
        $secret = $request->session()->get('2fa_setup_secret');

        if (!$secret) {
            return redirect()->route('2fa.index')->withErrors(['verify_code' => 'Sesi kedaluwarsa, silakan coba lagi.']);
        }

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($secret, $request->verify_code);

        if ($valid) {
            $user->google2fa_secret = $secret;
            $user->save();
            $request->session()->forget('2fa_setup_secret');
            
            \App\Models\ActivityLog::log('update_setting', 'Aktifkan 2FA', 'Mengaktifkan autentikasi dua langkah (2FA)');

            return redirect()->route('profile.edit')->with('status', '2fa-enabled');
        }

        return back()->withErrors(['verify_code' => 'Kode tidak valid. Silakan coba lagi.']);
    }

    public function disable(Request $request)
    {
        $user = $request->user();
        $user->google2fa_secret = null;
        $user->save();

        \App\Models\ActivityLog::log('update_setting', 'Nonaktifkan 2FA', 'Menonaktifkan autentikasi dua langkah (2FA)');

        return redirect()->route('profile.edit')->with('status', '2fa-disabled');
    }

    public function getChallenge(Request $request)
    {
        if (!$request->session()->has('2fa_pending_id')) {
            return redirect()->route('login');
        }

        return view('auth.2fa-challenge');
    }

    public function verifyChallenge(Request $request)
    {
        $request->validate([
            'verify_code' => 'required|string',
        ]);

        $userId = $request->session()->get('2fa_pending_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($userId);
        if (!$user) {
            return redirect()->route('login');
        }

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->verify_code);

        if ($valid) {
            $remember = $request->session()->get('2fa_remember', false);
            \Illuminate\Support\Facades\Auth::loginUsingId($user->id, $remember);
            $request->session()->forget(['2fa_pending_id', '2fa_remember']);
            
            // Log activity
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
            \App\Models\ActivityLog::log('login', 'Login berhasil (2FA)', "{$browser} · {$os} · {$request->ip()}");

            $url = '/';
            if ($user->role === 'admin') {
                $url = '/admin/dashboard';
            } elseif ($user->role === 'pegawai') {
                $url = '/pegawai/dashboard';
            } elseif ($user->role === 'user') {
                $url = '/user/dashboard';
            }

            return redirect()->intended($url);
        }

        return back()->withErrors(['verify_code' => 'Kode tidak valid. Silakan coba lagi.']);
    }
}
