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

        // Selalu prioritaskan Resend via HTTPS (Port 443) jika API key disetel di server
        if (env('RESEND_API_KEY')) {
            config([
                'mail.default' => 'resend',
                'services.resend.key' => env('RESEND_API_KEY'),
                'mail.from.address' => env('MAIL_FROM_ADDRESS', 'onboarding@resend.dev'),
                'mail.from.name' => env('MAIL_FROM_NAME', "OnHadir SMK Darus Sa'adah"),
            ]);
        }

        // Pastikan akun admin & petugas selalu tersedia di database server
        try {
            $dbPath = database_path('database.sqlite');
            if (config('database.default') === 'sqlite' && !file_exists($dbPath)) {
                @touch($dbPath);
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('users')) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
            } else {
                \App\Models\User::updateOrCreate(
                    ['email' => 'arrxptrr19@gmail.com'],
                    [
                        'name' => 'Arya Putra',
                        'password' => \Illuminate\Support\Facades\Hash::make('arya7777'),
                        'role' => 'admin',
                        'status' => 'aktif',
                    ]
                );

                \App\Models\User::updateOrCreate(
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
            \Illuminate\Support\Facades\Log::error('Auto DB setup error: ' . $e->getMessage());
        }
    }
}
