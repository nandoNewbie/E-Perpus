<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;     

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
    }
}