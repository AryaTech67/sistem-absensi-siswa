<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    /**
     * Tampilkan formulir permintaan tautan reset kata sandi (Lupa Password).
     */
    public function showLinkRequestForm(): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.forgot-password');
    }

    /**
     * Kirim tautan reset kata sandi ke email pengguna.
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Alamat email tidak terdaftar dalam sistem. Pastikan menggunakan email guru atau staf yang valid.',
            ])->withInput($request->only('email'));
        }

        if (!$user->isActive()) {
            return back()->withErrors([
                'email' => 'Akun ini dinonaktifkan oleh Administrator sekolah.',
            ])->withInput($request->only('email'));
        }

        // Kirim link reset melalui Password Broker Laravel
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()
                ->with('sent_email', $request->email);
        }

        return back()->withErrors([
            'email' => 'Gagal mengirim email reset kata sandi. Mohon coba beberapa saat lagi.',
        ])->withInput($request->only('email'));
    }

    /**
     * Tampilkan formulir untuk mengatur ulang kata sandi baru.
     */
    public function showResetForm(Request $request, ?string $token = null): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Proses pembaruan kata sandi baru.
     */
    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ], [
            'token.required' => 'Token reset kata sandi tidak ditemukan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi baru minimal harus 8 karakter.',
        ]);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('status', 'Kata sandi Anda berhasil diperbarui! Silakan masuk dengan kata sandi baru Anda.');
        }

        $errorMessage = match ($status) {
            Password::INVALID_USER => 'Alamat email tidak terdaftar dalam sistem.',
            Password::INVALID_TOKEN => 'Token reset kata sandi tidak valid atau sudah kedaluwarsa.',
            Password::RESET_THROTTLED => 'Terlalu banyak percobaan. Harap tunggu beberapa saat sebelum mencoba kembali.',
            default => 'Gagal memperbarui kata sandi. Silakan ulangi proses lupa sandi.',
        };

        return back()->withErrors(['email' => $errorMessage])->withInput($request->only('email'));
    }
}
