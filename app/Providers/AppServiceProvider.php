<?php

namespace App\Providers;

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
        \App\Models\Booking::observe(\App\Observers\BookingObserver::class);
        \App\Models\Payment::observe(\App\Observers\PaymentObserver::class);
        \App\Models\User::observe(\App\Observers\UserObserver::class);

        // Customize the Email Verification Template
        \Illuminate\Auth\Notifications\VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('✨ Verifikasi Email Anda - Imako Studio')
                ->greeting('Halo, ' . $notifiable->name . '!')
                ->line('Terima kasih telah mendaftar di Imako Studio! Langkah Anda tinggal sedikit lagi.')
                ->line('Sebelum Anda bisa masuk dan mulai memesan paket fotografi kami, silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda.')
                ->action('Verifikasi Email Saya', $url)
                ->line('Jika tombol di atas tidak berfungsi, Anda juga bisa menyalin dan menempelkan tautan berikut ke browser Anda:')
                ->line($url)
                ->line('Jika Anda merasa tidak pernah mendaftar akun di Imako Studio, silakan abaikan dan hapus email ini.');
        });

        // Customize the Password Reset Template
        \Illuminate\Auth\Notifications\ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('🔐 Reset Password - Imako Studio')
                ->greeting('Halo, ' . $notifiable->name . '!')
                ->line('Anda menerima email ini karena kami mendapat permintaan untuk mengatur ulang password akun Anda.')
                ->action('Atur Ulang Password', $url)
                ->line('Tautan reset password ini akan kedaluwarsa dalam 60 menit.')
                ->line('Jika Anda merasa tidak pernah meminta reset password, tidak ada tindakan lebih lanjut yang perlu Anda lakukan, abaikan saja email ini.');
        });
    }
}
