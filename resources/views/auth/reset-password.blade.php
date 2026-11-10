<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Kata Sandi — SMK Darus Sa'adah</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 lg:bg-white text-slate-800 antialiased min-h-screen">

    <div class="min-h-screen flex flex-col lg:flex-row">
        <!-- Left Side: Emerald Green Branding Hero -->
        <div class="hidden lg:flex lg:w-5/12 bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-950 relative overflow-hidden flex-col justify-between p-12 text-white">
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-emerald-500/15 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-amber-400/10 blur-3xl pointer-events-none"></div>

            <!-- Top Logo -->
            <div class="relative z-10">
                <a href="{{ route('home') }}" class="inline-flex items-center space-x-3.5 text-white">
                    <div class="w-12 h-12 rounded-xl bg-white p-1 shadow-lg shadow-emerald-950/40 flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Darus Sa'adah" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="text-xl font-extrabold tracking-tight block leading-tight">SMK DARUS SA'ADAH</span>
                        <span class="text-[11px] text-emerald-300 font-medium tracking-wide uppercase">Kata Sandi Baru</span>
                    </div>
                </a>
            </div>

            <!-- Middle Text & Tips -->
            <div class="relative z-10 max-w-md my-auto py-12">
                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center mb-6 text-emerald-300 border border-white/15">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h1 class="text-3xl xl:text-4xl font-extrabold text-white tracking-tight leading-snug mb-4">
                    Buat Kata Sandi Baru
                </h1>
                <p class="text-emerald-100/90 text-sm xl:text-base leading-relaxed mb-6">
                    Pastikan kata sandi baru Anda kuat dan belum pernah digunakan sebelumnya untuk melindungi integritas data presensi siswa.
                </p>
                <div class="space-y-2.5 text-xs text-emerald-100">
                    <div class="flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-300 font-bold text-[10px]">✓</span>
                        <span>Minimal 8 karakter kombinasi huruf & angka</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-300 font-bold text-[10px]">✓</span>
                        <span>Hindari tanggal lahir atau urutan angka berurutan</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="relative z-10 flex items-center justify-between text-xs text-emerald-200/80 pt-4 border-t border-white/10">
                <span>&copy; {{ date('Y') }} SMK Darus Sa'adah</span>
                <a href="{{ route('login') }}" class="text-amber-300 hover:text-amber-200 font-semibold underline underline-offset-2">
                    Batal & Kembali ke Login &rarr;
                </a>
            </div>
        </div>

        <!-- Right Side: Form Container -->
        <div class="w-full lg:w-7/12 flex flex-col justify-center items-center p-6 sm:p-10 lg:p-16">
            <div class="w-full max-w-md">
                <!-- Mobile Brand Header -->
                <div class="lg:hidden flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-white p-1 border border-slate-200 shadow-sm flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="text-lg font-bold text-slate-900 block leading-tight">SMK DARUS SA'ADAH</span>
                        <span class="text-[11px] text-emerald-700 font-semibold">Atur Ulang Sandi</span>
                    </div>
                </div>

                <!-- Form Heading -->
                <div class="mb-6">
                    <div class="inline-flex items-center space-x-2 text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full mb-3 border border-emerald-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <span>Verifikasi Token Berhasil</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Atur Ulang Sandi</h2>
                    <p class="text-sm text-slate-500 mt-1">Silakan masukkan kata sandi baru untuk akun Anda.</p>
                </div>

                <!-- Error Alert -->
                @if(isset($errors) && $errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium space-y-1">
                    @foreach($errors->all() as $error)
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>{{ $error }}</span>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Form Reset Password -->
                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/25 focus:border-emerald-600 transition placeholder:text-slate-400 bg-slate-50"
                            placeholder="nama@smkdarussaadah.sch.id" readonly>
                        <span class="text-[11px] text-slate-400 mt-1 block">Email ini terikat dengan tautan token keamanan Anda.</span>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Baru</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required autofocus
                                class="w-full px-4 py-3 pr-10 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/25 focus:border-emerald-600 transition placeholder:text-slate-400"
                                placeholder="Minimal 8 karakter">
                            <button type="button" onclick="togglePasswordVisibility('password', 'eyeIcon1')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                <svg id="eyeIcon1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full px-4 py-3 pr-10 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/25 focus:border-emerald-600 transition placeholder:text-slate-400"
                                placeholder="Ulangi kata sandi baru">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eyeIcon2')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                <svg id="eyeIcon2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-3.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-700/20 active:scale-[0.99] transition duration-150 flex items-center justify-center space-x-2">
                        <span>Simpan & Perbarui Kata Sandi</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </form>

                <!-- Back to Login Link -->
                <div class="mt-8 pt-6 border-t border-slate-200 text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center text-xs font-bold text-slate-600 hover:text-emerald-700 transition">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Batal dan Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(fieldId, iconId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }
    </script>
</body>
</html>
