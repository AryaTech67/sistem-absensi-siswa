<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi — SMK Darus Sa'adah</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; color: #1e293b;">
    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f1f5f9; padding: 40px 15px;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 580px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;">
                    
                    <!-- Header with Emerald Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #059669 100%); padding: 32px 30px; text-align: center;">
                            <div style="font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px; margin-bottom: 4px;">
                                SMK DARUS SA'ADAH
                            </div>
                            <div style="font-size: 13px; font-weight: 600; color: #a7f3d0; text-transform: uppercase; letter-spacing: 1px;">
                                OnHadir — Sistem Informasi Presensi Siswa
                            </div>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 36px 32px 28px 32px;">
                            <h1 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 16px 0;">
                                Halo, {{ $user->name }}!
                            </h1>
                            <p style="font-size: 14px; line-height: 24px; color: #475569; margin: 0 0 20px 0;">
                                Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda di aplikasi <strong>OnHadir SMK Darus Sa'adah</strong>.
                            </p>
                            <p style="font-size: 14px; line-height: 24px; color: #475569; margin: 0 0 28px 0;">
                                Silakan klik tombol di bawah ini untuk membuat kata sandi baru. Tautan ini hanya berlaku selama <strong>{{ $expireMinutes }} menit</strong> demi keamanan akun Anda.
                            </p>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ $resetUrl }}" style="display: inline-block; background-color: #059669; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 10px; font-weight: 700; font-size: 14px; letter-spacing: 0.3px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);">
                                    Atur Ulang Kata Sandi
                                </a>
                            </div>

                            <!-- Security Notice Box -->
                            <div style="background-color: #f8fafc; border-left: 4px solid #f59e0b; padding: 14px 16px; border-radius: 6px; margin: 26px 0 20px 0;">
                                <p style="font-size: 12px; line-height: 18px; color: #64748b; margin: 0;">
                                    <strong>Keamanan:</strong> Jika Anda tidak pernah meminta perubahan kata sandi ini, silakan abaikan email ini. Akun Anda tetap aman dan kata sandi tidak akan berubah.
                                </p>
                            </div>

                            <!-- Fallback Link -->
                            <p style="font-size: 12px; line-height: 18px; color: #94a3b8; margin: 24px 0 0 0; word-break: break-all; border-top: 1px solid #f1f5f9; padding-top: 20px;">
                                Mengalami masalah saat mengklik tombol? Salin tautan berikut ke browser Anda:<br>
                                <a href="{{ $resetUrl }}" style="color: #059669; text-decoration: underline;">{{ $resetUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px 32px; text-align: center; border-top: 1px solid #e2e8f0;">
                            <p style="font-size: 12px; color: #94a3b8; margin: 0 0 4px 0;">
                                &copy; {{ date('Y') }} SMK Darus Sa'adah &bull; Sistem Presensi & QR Smartcard
                            </p>
                            <p style="font-size: 11px; color: #cbd5e1; margin: 0;">
                                Pesan ini dikirim secara otomatis oleh sistem, mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
