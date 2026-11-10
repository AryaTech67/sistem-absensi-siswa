<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi — SMK Darus Sa'adah</title>
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
                        <span class="text-[11px] text-emerald-300 font-medium tracking-wide uppercase">Pemulihan Akun</span>
                    </div>
                </a>
            </div>

            <!-- Middle Text -->
            <div class="relative z-10 max-w-md my-auto py-12">
                <h1 class="text-3xl xl:text-4xl font-extrabold text-white tracking-tight leading-snug mb-4">
                    Sistem Presensi & Disiplin Siswa
                </h1>
                <p class="text-emerald-100/90 text-sm xl:text-base leading-relaxed mb-6">
                    Akses portal pengelolaan absensi dan kartu pintar siswa terintegrasi.
                </p>
                <div class="p-4 rounded-xl bg-white/5 border border-white/10 text-xs text-emerald-100/90 leading-relaxed">
                    <span class="font-semibold text-white block mb-1">Catatan Keamanan</span>
                    Tautan pemulihan kata sandi memiliki masa kedaluwarsa 60 menit. Pastikan email yang dimasukkan telah didaftarkan oleh Administrator.
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="relative z-10 flex items-center justify-between text-xs text-emerald-200/80 pt-4 border-t border-white/10">
                <span>&copy; {{ date('Y') }} SMK Darus Sa'adah</span>
                <a href="{{ route('login') }}" class="text-amber-300 hover:text-amber-200 font-semibold underline underline-offset-2">
                    Kembali ke Login &rarr;
                </a>
            </div>
        </div>

        <!-- Right Side: Clean Form or Confirmation State -->
        <div class="w-full lg:w-7/12 flex flex-col justify-center items-center p-6 sm:p-10 lg:p-16">
            <div class="w-full max-w-md">
                <!-- Mobile Brand Header -->
                <div class="lg:hidden flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-white p-1 border border-slate-200 shadow-sm flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="text-lg font-bold text-slate-900 block leading-tight">SMK DARUS SA'ADAH</span>
                        <span class="text-[11px] text-emerald-700 font-semibold">Lupa Kata Sandi</span>
                    </div>
                </div>

                @if (session('sent_email'))
                <!-- STATE 1: SUCCESS CONFIRMATION (Check Email View - Stripe/Linear Style) -->
                <div class="text-center">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center mx-auto mb-5 shadow-xs">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>

                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight mb-2">Periksa Email Anda</h2>
                    <p class="text-sm text-slate-500 mb-4 leading-relaxed">
                        Kami telah mengirimkan tautan reset kata sandi ke:
                    </p>

                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-slate-800 font-semibold text-sm font-mono mb-4 break-all">
                        {{ session('sent_email') }}
                    </div>

                    <p class="text-xs text-slate-500 leading-relaxed mb-6">
                        Tautan ini hanya berlaku selama <strong class="text-slate-700">60 menit</strong> demi alasan keamanan. Periksa juga folder <em>Spam</em> atau <em>Promosi</em> jika email tidak segera muncul.
                    </p>

                    <div class="space-y-3">
                        <a href="https://mail.google.com" target="_blank"
                            class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-sm transition flex items-center justify-center space-x-2">
                            <span>Buka Gmail</span>
                            <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>

                        <a href="{{ route('password.request') }}"
                            class="block text-xs font-semibold text-slate-600 hover:text-slate-900 py-2 transition">
                            Kirim ulang atau gunakan email lain
                        </a>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-200">
                        <a href="{{ route('login') }}" class="inline-flex items-center text-xs font-medium text-slate-500 hover:text-slate-800 transition">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali ke Halaman Masuk
                        </a>
                    </div>
                </div>

                @else
                <!-- STATE 2: INPUT FORM -->
                <div>
                    <!-- Form Heading -->
                    <div class="mb-6">
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Lupa Kata Sandi?</h2>
                        <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                            Masukkan alamat email akun Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
                        </p>
                    </div>

                    <!-- Clean Error Alert -->
                    @if(isset($errors) && $errors->has('email'))
                    <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 flex items-start space-x-2.5 text-xs text-rose-700">
                        <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="leading-relaxed">{{ $errors->first('email') }}</span>
                    </div>
                    @endif

                    <!-- Form Request Reset Link -->
                    <form method="POST" action="{{ route('password.email') }}" class="space-y-4" id="forgotForm">
                        @csrf
                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full px-4 py-3 rounded-xl border text-sm transition placeholder:text-slate-400 focus:outline-none focus:ring-2 {{ isset($errors) && $errors->has('email') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500/20 text-rose-900 bg-rose-50/20' : 'border-slate-300 focus:border-emerald-600 focus:ring-emerald-500/20' }}"
                                placeholder="arrxptrr19@gmail.com">
                        </div>

                        <button type="submit" id="btnSubmit"
                            class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-sm shadow-xs active:scale-[0.99] transition duration-150 flex items-center justify-center space-x-2 cursor-pointer">
                            <span id="btnText">Kirim Tautan Pemulihan</span>
                            <svg id="btnIcon" class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                            <svg id="btnSpinner" class="hidden animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </button>
                    </form>

                    <!-- Back to Login Link -->
                    <div class="mt-8 pt-6 border-t border-slate-200 text-center">
                        <a href="{{ route('login') }}" class="inline-flex items-center text-xs font-medium text-slate-500 hover:text-slate-800 transition">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali ke Halaman Masuk
                        </a>
                    </div>
                </div>

                <script>
                    document.getElementById('forgotForm')?.addEventListener('submit', function() {
                        const btn = document.getElementById('btnSubmit');
                        const text = document.getElementById('btnText');
                        const icon = document.getElementById('btnIcon');
                        const spinner = document.getElementById('btnSpinner');

                        btn.disabled = true;
                        btn.classList.add('opacity-75', 'cursor-not-allowed');
                        text.textContent = 'Mengirim Tautan...';
                        icon.classList.add('hidden');
                        spinner.classList.remove('hidden');
                    });
                </script>
                @endif

            </div>
        </div>
    </div>

</body>
</html>
