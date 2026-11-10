<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // Paksa skema HTTPS di server cloud (Railway / Heroku / Reverse Proxy)
        if (app()->environment('production') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            URL::forceScheme('https');
        }

        // Pastikan akun admin & petugas selalu tersedia di database server
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
                \App\Models\User::firstOrCreate(
                    ['email' => 'arrxptrr19@gmail.com'],
                    [
                        'name' => 'Arya Putra',
                        'password' => \Illuminate\Support\Facades\Hash::make('arya7777'),
                        'role' => 'admin',
                        'status' => 'aktif',
                    ]
                );

                \App\Models\User::firstOrCreate(
                    ['email' => 'suteja77@gmail.com'],
                    [
                        'name' => 'Suteja',
                        'password' => \Illuminate\Support\Facades\Hash::make('suteja7777'),
                        'role' => 'petugas',
                        'status' => 'aktif',
                    ]
                );
            }
        } catch (\Throwable $e) {
            // abaikan jika tabel belum siap
        }
    }
}
