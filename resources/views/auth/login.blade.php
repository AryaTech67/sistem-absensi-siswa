<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SMK Darus Sa'adah</title>
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
                            600: '#059669', // Emerald SMK Darussa'adah
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
        <!-- Left Side: Emerald Green Branding Hero (Visible on Desktop / Tablets) -->
        <div class="hidden lg:flex lg:w-5/12 bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-950 relative overflow-hidden flex-col justify-between p-12 text-white">
            <!-- Background Abstract Glow/Circles -->
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
                        <span class="text-[11px] text-emerald-300 font-medium tracking-wide uppercase">Presensi & Disiplin Digital</span>
                    </div>
                </a>
            </div>

            <!-- Middle Text & Pills -->
            <div class="relative z-10 max-w-md my-auto py-12">
                <h1 class="text-3xl xl:text-4xl font-extrabold text-white tracking-tight leading-snug mb-4">
                    Sistem Presensi & Disiplin Siswa
                </h1>
                <p class="text-emerald-100/90 text-sm xl:text-base leading-relaxed mb-8">
                    Kelola absensi harian berbasis scan QR Code, deteksi keterlambatan jam pelajaran, dan rekapitulasi kehadiran resmi secara akurat.
                </p>
                <div class="flex flex-wrap gap-2.5">
                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full bg-white/10 text-xs font-semibold text-emerald-100 backdrop-blur-md border border-white/15">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2 shadow-xs"></span>
                        Scan QR Cepat
                    </span>
                    <span class="inline-flex items-center px-3.5 py-1.5 rounded-full bg-white/10 text-xs font-semibold text-amber-200 backdrop-blur-md border border-white/15">
                        <span class="w-2 h-2 rounded-full bg-amber-400 mr-2 shadow-xs"></span>
                        Buku Saku BK & Jam Terlewat
                    </span>
                </div>
            </div>

            <!-- Bottom Copyright & Public Check Link -->
            <div class="relative z-10 flex items-center justify-between text-xs text-emerald-200/80 pt-4 border-t border-white/10">
                <span>&copy; {{ date('Y') }} SMK Darus Sa'adah</span>
                <a href="{{ route('public.check') }}" class="text-amber-300 hover:text-amber-200 font-semibold underline underline-offset-2">
                    Portal Cek Mandiri Siswa &rarr;
                </a>
            </div>
        </div>

        <!-- Right Side: Clean White Form -->
        <div class="w-full lg:w-7/12 flex flex-col justify-center items-center p-6 sm:p-10 lg:p-16">
            <div class="w-full max-w-md">
                <!-- Mobile Brand Header (Visible only on small screens) -->
                <div class="lg:hidden flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-white p-1 border border-slate-200 shadow-sm flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <span class="text-lg font-bold text-slate-900 block leading-tight">SMK DARUS SA'ADAH</span>
                        <span class="text-[11px] text-emerald-700 font-semibold">Presensi Digital Siswa</span>
                    </div>
                </div>

                <!-- Form Heading -->
                <div class="mb-6">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Masuk Sistem</h2>
                    <p class="text-sm text-slate-500 mt-1">Silakan masuk menggunakan akun guru atau staf sekolah</p>
                </div>

                <!-- Alerts -->
                @if(session('status'))
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center space-x-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
                @endif

                @if(session('info'))
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center space-x-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('info') }}</span>
                </div>
                @endif

                @if(isset($errors) && $errors->has('email'))
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium flex items-center space-x-2">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $errors->first('email') }}</span>
                </div>
                @endif

                <!-- Form Login -->
                <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Email Pengguna</label>
                        <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/25 focus:border-emerald-600 transition placeholder:text-slate-400"
                            placeholder="arrxptrr19@gmail.com">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-slate-700">Password</label>
                            <a href="{{ route('password.request') }}" class="text-xs text-emerald-700 hover:text-emerald-800 font-semibold transition">Lupa password?</a>
                        </div>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                class="w-full px-4 py-3 pr-10 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/25 focus:border-emerald-600 transition placeholder:text-slate-400"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                <svg id="eyeIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300">
                            <span class="text-xs text-slate-600 select-none">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-md shadow-emerald-600/20 transition duration-150">
                        Masuk ke Dashboard
                    </button>
                </form>

                <!-- Public Portal Link for Parents / Students -->
                <div class="mt-6 pt-5 border-t border-slate-200 text-center">
                    <p class="text-xs text-slate-500 mb-2">Siswa atau Orang Tua yang ingin memantau absensi?</p>
                    <a href="{{ route('public.check') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 px-3.5 py-2 rounded-lg border border-emerald-200 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>Buka Portal Cek Mandiri Kehadiran</span>
                    </a>
                </div>

                <!-- Quick Test Login Credentials -->
                <div class="mt-6 pt-5 border-t border-slate-100">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center mb-2.5">Akun Pengujian Cepat</p>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <button type="button" onclick="fillCredentials('arrxptrr19@gmail.com', 'arya7777')"
                            class="p-2.5 rounded-lg border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 transition text-left group">
                            <span class="font-bold text-slate-800 block text-[11px] group-hover:text-emerald-700">Administrator</span>
                            <span class="text-[10px] text-slate-500 block truncate">arrxptrr19@gmail.com</span>
                        </button>
                        <button type="button" onclick="fillCredentials('suteja77@gmail.com', 'suteja7777')"
                            class="p-2.5 rounded-lg border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/50 transition text-left group">
                            <span class="font-bold text-slate-800 block text-[11px] group-hover:text-emerald-700">Petugas Presensi</span>
                            <span class="text-[10px] text-slate-500 block truncate">suteja77@gmail.com</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                `;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        }

        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
