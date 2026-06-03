<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;     
use Illuminate\Support\Facades\URL;

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
        // 3. Tangkap momen ketika ada user yang berhasil login
        Event::listen(Login::class, function ($event) {
            // Update kolom last_login_at milik user tersebut dengan waktu sekarang
            $event->user->update([
                'last_login_at' => now()
            ]);
        });
        // Jika diakses lewat ngrok, paksa skema URL menggunakan HTTPS publik
        if (str_contains(request()->url(), 'ngrok-free.app')) {
            URL::forceScheme('https');
    }
    }
}