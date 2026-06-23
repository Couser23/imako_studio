<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View|RedirectResponse
    {
        $stats = [];
        if ($request->user()->role === 'admin') {
            $stats = [
                'bookings_this_month' => \App\Models\Booking::whereMonth('booking_date', now()->month)->count(),
                'active_employees' => \App\Models\User::where('role', 'pegawai')->count(),
                'income_this_month' => \App\Models\Payment::whereHas('booking', function ($q) {
                    $q->whereMonth('booking_date', now()->month);
                })->where('status', 'verified')->sum('amount'),
            ];
        }

        $studioSetting = \App\Models\StudioSetting::first() ?? new \App\Models\StudioSetting([
            'name' => 'Imako Studio',
            'tagline' => 'Abadikan Momenmu Bersama Kami',
            'address' => 'Jl. Raya Malang No. 42, Lowokwaru, Kota Malang, Jawa Timur 65141',
            'phone' => '0341-555-1234',
            'whatsapp' => '0812-3456-7890',
            'email' => 'hello@imako.studio',
            'open_time' => '09:00',
            'close_time' => '18:00',
            'operational_days' => ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            'min_booking_hours' => 3,
            'max_booking_months' => 3,
            'instagram' => '@imako.studio',
            'facebook' => 'Imako Studio Official',
            'whatsapp_link' => 'https://wa.me/6281234567890'
        ]);
        $activityLogs = \App\Models\ActivityLog::where('user_id', $request->user()->id)
            ->latest()
            ->take(20)
            ->get();

        $notifPrefs = $request->user()->notification_preferences ?? [
            'booking_baru' => true,
            'pembayaran_diterima' => true,
            'booking_dibatalkan' => true,
            'pengingat_sesi' => true,
            'laporan_mingguan' => false,
            'pegawai_baru' => false,
            'notifikasi_realtime' => true,
            'validasi_menunggu' => true,
            'hasil_belum_dikirim' => true,
            'sesi_akan_dimulai' => false,
            'report_freq' => 'harian',
            'notification_sound' => 'Ting (Default)',
        ];

        $closedDates = \App\Models\ClosedDate::orderBy('start_date', 'asc')->get();

        $sessions = \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) use ($request) {
                $agent = $this->createAgent($session->user_agent);

                return (object) [
                    'id' => $session->id,
                    'agent' => [
                        'is_desktop' => $agent->isDesktop(),
                        'platform' => $agent->platform(),
                        'browser' => $agent->browser(),
                    ],
                    'ip_address' => $session->ip_address,
                    'is_current_device' => $session->id === $request->session()->getId(),
                    'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                ];
            });

        if ($request->user()->role === 'pegawai') {
            // Redirect to the dedicated employee profile controller to ensure all stats/variables are loaded
            return redirect()->route('employee.profile.edit');
        }

        if ($request->user()->role !== 'admin') {
            return view('user.profile.edit', [
                'user' => $request->user(),
                'sessions' => $sessions,
                'activityLogs' => $activityLogs,
            ]);
        }

        return view('admin.profile.edit', [
            'user' => $request->user(),
            'stats' => $stats,
            'studioSetting' => $studioSetting,
            'activityLogs' => $activityLogs,
            'notifPrefs' => $notifPrefs,
            'closedDates' => $closedDates,
            'sessions' => $sessions,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $roleFolder = strtolower($user->role ?? 'user');

        if ($request->hasFile('avatar')) {
            $oldAvatar = $user->getOriginal('avatar');
            if ($oldAvatar && file_exists(public_path('images/profile_akun/' . $roleFolder . '/' . $oldAvatar))) {
                unlink(public_path('images/profile_akun/' . $roleFolder . '/' . $oldAvatar));
            }
            $avatarName = time() . '_avatar.' . $request->avatar->extension();
            $request->avatar->move(public_path('images/profile_akun/' . $roleFolder), $avatarName);
            $user->avatar = $avatarName;
        }

        if ($request->hasFile('cover_image')) {
            $oldCover = $user->getOriginal('cover_image');
            if ($oldCover && file_exists(public_path('images/cover_profile/' . $roleFolder . '/' . $oldCover))) {
                unlink(public_path('images/cover_profile/' . $roleFolder . '/' . $oldCover));
            }
            $coverName = time() . '_cover.' . $request->cover_image->extension();
            $request->cover_image->move(public_path('images/cover_profile/' . $roleFolder), $coverName);
            $user->cover_image = $coverName;
        }

        $user->save();

        \App\Models\ActivityLog::log('update_setting', 'Ubah profil akun', 'Memperbarui data profil');

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's avatar via AJAX.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        $user = $request->user();
        $roleFolder = strtolower($user->role ?? 'user');

        $oldAvatar = $user->getOriginal('avatar');
        if ($oldAvatar && file_exists(public_path('images/profile_akun/' . $roleFolder . '/' . $oldAvatar))) {
            unlink(public_path('images/profile_akun/' . $roleFolder . '/' . $oldAvatar));
        }
        
        $avatarName = time() . '_avatar.' . $request->avatar->extension();
        $request->avatar->move(public_path('images/profile_akun/' . $roleFolder), $avatarName);
        
        $user->avatar = $avatarName;
        $user->save();

        return response()->json([
            'success' => true,
            'avatar_url' => asset('images/profile_akun/' . $roleFolder . '/' . $avatarName)
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Delete other browser sessions.
     */
    public function destroySessions(\Illuminate\Http\Request $request)
    {
        $otherSessions = \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->where('id', '!=', $request->session()->getId())
            ->get();

        foreach($otherSessions as $session) {
            try {
                event(new \App\Events\SessionTerminated($session->id));
            } catch (\Exception $e) {
                // Ignore broadcast exception if Reverb server is offline
            }
        }

        \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        return \Illuminate\Support\Facades\Redirect::route('profile.edit')->with('status', 'password-updated'); // Use password-updated status to reopen the security tab
    }

    /**
     * Delete a specific browser session.
     */
    public function destroySession(\Illuminate\Http\Request $request, $id)
    {
        try {
            event(new \App\Events\SessionTerminated($id));
        } catch (\Exception $e) {
            // Ignore broadcast exception if Reverb server is offline
        }

        \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->where('id', $id)
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        return \Illuminate\Support\Facades\Redirect::route('profile.edit')->with('status', 'password-updated');
    }

    /**
     * Return active sessions HTML for polling.
     */
    public function sessionsHtml(\Illuminate\Http\Request $request)
    {
        $sessions = \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', $request->user()->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) use ($request) {
                $agent = $this->createAgent($session->user_agent);

                return (object) [
                    'id' => $session->id,
                    'agent' => [
                        'is_desktop' => $agent->isDesktop(),
                        'platform' => $agent->platform(),
                        'browser' => $agent->browser(),
                    ],
                    'ip_address' => $session->ip_address,
                    'is_current_device' => $session->id === $request->session()->getId(),
                    'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                ];
            });

        return view('admin.profile.partials.tab-keamanan-sessions', compact('sessions'))->render();
    }

    /**
     * Create a new agent instance from the given session.
     *
     * @param  string  $userAgent
     * @return \Jenssegers\Agent\Agent
     */
    protected function createAgent($userAgent)
    {
        return tap(new \Jenssegers\Agent\Agent, function ($agent) use ($userAgent) {
            $agent->setUserAgent($userAgent);
        });
    }
}
