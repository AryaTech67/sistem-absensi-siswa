<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Sesi Anda telah kedaluwarsa. Silakan refresh halaman.'], 419);
            }
            if (!auth()->check() || $request->is('login') || $request->is('login/*')) {
                return redirect()->route('login')->with('info', 'Sesi Anda telah kedaluwarsa demi keamanan. Silakan coba masuk kembali.');
            }
            return redirect()->back()->withInput()->with('error', 'Sesi Anda telah kedaluwarsa. Silakan muat ulang halaman dan coba lagi.');
        });
    })->create();
