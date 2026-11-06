<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnHadir — Sistem Presensi Sekolah Online Terpadu</title>
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
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb', // Royal Electric Blue khas OnHadir
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
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
<body class="bg-white text-slate-800 antialiased selection:bg-brand-500 selection:text-white">

    <!-- Navbar Sticky Header -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-brand-600 flex items-center justify-center text-white shadow-md shadow-brand-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-slate-900">OnHadir</span>
            </a>

            <!-- Navigation Links & CTA -->
            <div class="flex items-center space-x-3 sm:space-x-5">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 px-3 py-2 transition">
                    Masuk
                </a>
                <a href="{{ route('login') }}" class="text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 px-4 sm:px-5 py-2.5 rounded-xl shadow-md shadow-brand-500/20 transition duration-150 inline-flex items-center">
                    <span>Daftarkan Sekolah</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="pt-12 sm:pt-20 pb-16 sm:pb-24 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                <!-- Left Hero Content -->
                <div class="lg:col-span-7 space-y-6 text-left">
                    <div class="inline-flex items-center space-x-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-brand-600">06:58 · SEBELUM BEL</span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15]">
                        Sistem Presensi Sekolah Online — <span class="text-brand-600">Siswa, Guru & Staff</span> dalam Satu Aplikasi
                    </h1>

                    <p class="text-base sm:text-lg text-slate-600 leading-relaxed max-w-2xl">
                        Siswa scan QR di gerbang, guru dan staff absen sendiri lewat HP atau kiosk yang sama, dan rekap semester sudah rapi tanpa direkap ulang manual. Satu sistem, dari SD sampai SMA.
                    </p>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-base shadow-lg shadow-brand-500/25 transition">
                            <span>Daftarkan Sekolah — Gratis</span>
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                        <a href="#cara-kerja" class="inline-flex items-center justify-center px-6 py-3.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-semibold text-base border border-slate-200 transition">
                            Lihat cara kerjanya
                        </a>
                    </div>

                    <p class="text-xs text-slate-400">
                        Tanpa kartu kredit. Sampai 100 siswa selamanya gratis — plus semua fitur berbayar gratis 14 hari pertama.
                    </p>
                </div>

                <!-- Right Hero Simulated Live Attendance Card (Roster VII A) -->
                <div class="lg:col-span-5 flex justify-center lg:justify-end">
                    <div class="w-full max-w-md bg-white rounded-xl border border-slate-200/90 shadow-2xl p-6 relative">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">Roster · VII A</h3>
                                <p class="text-xs text-slate-400 mt-0.5">Kamis, 07:02</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-brand-50 text-brand-600 border border-brand-100">
                                5/6 hadir
                            </span>
                        </div>

                        <!-- Student List Rows -->
                        <div class="space-y-3.5">
                            <!-- Ahmad Fauzi -->
                            <div class="flex items-center justify-between py-1">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Ahmad Fauzi</p>
                                    <p class="text-xs text-slate-400">06:52</p>
                                </div>
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    Hadir
                                </span>
                            </div>

                            <!-- Siti Rahayu -->
                            <div class="flex items-center justify-between py-1">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Siti Rahayu</p>
                                    <p class="text-xs text-slate-400">07:04</p>
                                </div>
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-amber-50 text-amber-700 border border-amber-200">
                                    Terlambat
                                </span>
                            </div>

                            <!-- Budi Santoso -->
                            <div class="flex items-center justify-between py-1">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Budi Santoso</p>
                                    <p class="text-xs text-slate-400">06:58</p>
                                </div>
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    Hadir
                                </span>
                            </div>

                            <!-- Dewi Lestari -->
                            <div class="flex items-center justify-between py-1">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Dewi Lestari</p>
                                    <p class="text-xs text-slate-400">—</p>
                                </div>
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-sky-50 text-sky-600 border border-sky-100">
                                    Izin
                                </span>
                            </div>

                            <!-- Rizky Pratama -->
                            <div class="flex items-center justify-between py-1">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Rizky Pratama</p>
                                    <p class="text-xs text-slate-400">06:55</p>
                                </div>
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    Hadir
                                </span>
                            </div>

                            <!-- Nadia Putri -->
                            <div class="flex items-center justify-between py-1">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">Nadia Putri</p>
                                    <p class="text-xs text-slate-400">07:01</p>
                                </div>
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    Hadir
                                </span>
                            </div>
                        </div>

                        <!-- Simulation Footer Caption -->
                        <p class="text-[11px] text-slate-400 text-center mt-5 pt-3 border-t border-slate-100">
                            Simulasi tampilan — bukan data sekolah sungguhan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cara Kerja Section (Timeline Vertikal) -->
    <section id="cara-kerja" class="py-16 sm:py-24 bg-slate-50/70 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-left mb-12 sm:mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600">CARA KERJA</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 mt-2">
                    Bergerak sesuai jam sekolah, dari gerbang sampai rapor
                </h2>
            </div>

            <!-- Timeline Nodes List -->
            <div class="relative border-l-2 border-brand-200 ml-4 sm:ml-6 space-y-12 sm:space-y-16 pl-6 sm:pl-10">
                <!-- Node 1: 07:00 Gerbang Dibuka -->
                <div class="relative">
                    <!-- Timeline Dot -->
                    <div class="absolute -left-[31px] sm:-left-[47px] top-1 w-4 h-4 rounded-full bg-brand-600 ring-4 ring-white shadow"></div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-7 space-y-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">07:00 · GERBANG DIBUKA</span>
                            <h3 class="text-xl sm:text-2xl font-bold text-slate-900">Scan QR, langsung tercatat</h3>
                            <ul class="space-y-3 text-sm text-slate-600 pt-2">
                                <li class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                    <span><strong>Setiap siswa punya QR sendiri</strong> — scan sekali, status langsung Hadir atau Terlambat sesuai jam masuk sekolah.</span>
                                </li>
                                <li class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    <span>Kartu pelajar dan QR bisa dicetak langsung dari sistem, satu per satu atau sekaligus untuk satu kelas.</span>
                                </li>
                                <li class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    <span>Bisa juga pakai kartu RFID — tempel di pembaca yang dicolok ke laptop, atau perangkat mandiri di gerbang.</span>
                                </li>
                                <li class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>Guru tetap bisa input manual kalau ada siswa lupa bawa kartu — tidak ada yang tertinggal dari rekap.</span>
                                </li>
                            </ul>
                        </div>
                        <div class="lg:col-span-5 flex justify-center">
                            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md p-6 text-center w-full max-w-sm">
                                <div class="w-32 h-32 mx-auto bg-slate-900 rounded-xl flex items-center justify-center p-3 text-white mb-4">
                                    <svg class="w-24 h-24 text-white" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M2 2h8v8H2V2zm2 2v4h4V4H4zm10-2h8v8h-8V2zm2 2v4h4V4h-4zM2 14h8v8H2v-8zm2 2v4h4v-4H4zm12 0h2v2h-2v-2zm-2 2h2v4h-2v-4zm4 2h4v2h-4v-2zm2-4h2v2h-2v-2zm-4-2h2v2h-2v-2zm4 0h2v2h-2v-2z"/>
                                    </svg>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                    Hadir · Tepat Waktu
                                </span>
                                <p class="text-xs text-slate-400 mt-2">06:58:12</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Node 2: Sepanjang Hari Jam Pelajaran -->
                <div class="relative">
                    <div class="absolute -left-[31px] sm:-left-[47px] top-1 w-4 h-4 rounded-full bg-brand-600 ring-4 ring-white shadow"></div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-7 space-y-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">SEPANJANG HARI · JAM PELAJARAN</span>
                            <h3 class="text-xl sm:text-2xl font-bold text-slate-900">Guru mapel absen sesuai jadwalnya sendiri</h3>
                            <p class="text-sm text-slate-600 leading-relaxed">
                                Untuk SMP dan SMA yang absen per jam pelajaran: jadwal kelas &times; mapel &times; guru diatur sekali, sistem otomatis menolak jadwal yang bentrok, dan setiap guru hanya melihat kelas yang memang jadwalnya hari itu.
                            </p>
                        </div>
                        <div class="lg:col-span-5 flex justify-center">
                            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md p-5 w-full max-w-sm">
                                <h4 class="font-bold text-slate-900 text-sm mb-3">Jadwal · Kelas VIII A</h4>
                                <div class="space-y-2 text-xs">
                                    <div class="p-2.5 rounded-lg bg-slate-50 flex items-center justify-between border border-slate-100">
                                        <span class="font-semibold text-slate-700">Jam ke-1</span>
                                        <span class="font-bold text-slate-900">Matematika</span>
                                        <span class="text-slate-500">Bu Siti</span>
                                    </div>
                                    <div class="p-2.5 rounded-lg bg-slate-50 flex items-center justify-between border border-slate-100">
                                        <span class="font-semibold text-slate-700">Jam ke-2</span>
                                        <span class="font-bold text-slate-900">Matematika</span>
                                        <span class="text-slate-500">Bu Siti</span>
                                    </div>
                                    <div class="p-2.5 rounded-lg bg-slate-50 flex items-center justify-between border border-slate-100">
                                        <span class="font-semibold text-slate-700">Jam ke-3</span>
                                        <span class="font-bold text-slate-900">Bahasa Indonesia</span>
                                        <span class="text-slate-500">Pak Hendra</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Node 3: 16:00 Selesai Dengan Sendirinya -->
                <div class="relative">
                    <div class="absolute -left-[31px] sm:-left-[47px] top-1 w-4 h-4 rounded-full bg-brand-600 ring-4 ring-white shadow"></div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-7 space-y-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">16:00 · SELESAI DENGAN SENDIRINYA</span>
                            <h3 class="text-xl sm:text-2xl font-bold text-slate-900">Tidak ada yang perlu dikejar manual</h3>
                            <ul class="space-y-3 text-sm text-slate-600 pt-2">
                                <li class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    <span>Siswa yang belum sempat diabsen otomatis tercatat Alfa jam 16:00 — hari libur dan akhir pekan dilewati.</span>
                                </li>
                                <li class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    <span>Orang tua mendapat notifikasi langsung di HP dan bisa membuka portal kehadiran anak tanpa perlu login.</span>
                                </li>
                            </ul>
                        </div>
                        <div class="lg:col-span-5 flex justify-center">
                            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md p-5 w-full max-w-sm">
                                <div class="flex items-center space-x-2 text-xs font-semibold text-slate-500 mb-3">
                                    <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Alfa otomatis dijalankan · Hari ini, 16:00</span>
                                </div>
                                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-900 leading-relaxed">
                                    <p class="font-semibold text-[11px] text-amber-700 uppercase tracking-wide mb-1">Notifikasi ke Orang Tua</p>
                                    "Fajar Ramadhan belum hadir hingga akhir hari ini (Kamis, 10 Jul 2026)."
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Node 4: Setiap Saat Rekap & Laporan -->
                <div class="relative">
                    <div class="absolute -left-[31px] sm:-left-[47px] top-1 w-4 h-4 rounded-full bg-brand-600 ring-4 ring-white shadow"></div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-7 space-y-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">SETIAP SAAT · REKAP & LAPORAN</span>
                            <h3 class="text-xl sm:text-2xl font-bold text-slate-900">Rekap sudah rapi saat dibutuhkan</h3>
                            <ul class="space-y-3 text-sm text-slate-600 pt-2">
                                <li class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    <span>Dashboard admin dan guru menampilkan tren kehadiran 30 hari terakhir, per kelas maupun per siswa.</span>
                                </li>
                                <li class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>Export rekap semester ke PDF atau Excel, siap dilampirkan ke laporan Dinas Pendidikan.</span>
                                </li>
                                <li class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Setiap koreksi status kehadiran tercatat di riwayat — jelas siapa mengubah apa dan kapan.</span>
                                </li>
                            </ul>
                        </div>
                        <div class="lg:col-span-5 flex justify-center">
                            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-md p-5 w-full max-w-sm">
                                <h4 class="font-bold text-slate-900 text-sm mb-4">Rekap Semester Ganjil</h4>
                                <div class="space-y-3 text-xs">
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="font-semibold text-slate-700">Hadir</span>
                                            <span class="font-bold text-emerald-600">92%</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2">
                                            <div class="bg-emerald-500 h-2 rounded-full" style="width: 92%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="font-semibold text-slate-700">Izin</span>
                                            <span class="font-bold text-brand-600">4%</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2">
                                            <div class="bg-brand-500 h-2 rounded-full" style="width: 4%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="font-semibold text-slate-700">Sakit</span>
                                            <span class="font-bold text-amber-600">3%</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2">
                                            <div class="bg-amber-500 h-2 rounded-full" style="width: 3%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex justify-between mb-1">
                                            <span class="font-semibold text-slate-700">Alfa</span>
                                            <span class="font-bold text-rose-600">1%</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2">
                                            <div class="bg-rose-500 h-2 rounded-full" style="width: 1%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mode Absensi Section -->
    <section class="py-16 sm:py-24 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-left mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600">MODE ABSENSI</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 mt-2">
                    Sesuaikan dengan cara sekolah Anda absen
                </h2>
                <p class="text-sm sm:text-base text-slate-600 mt-2 max-w-2xl">
                    Satu sistem, dua mode — dipilih sekali saat mendaftar, bisa diubah kapan pun lewat halaman pengaturan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Card Mode Harian -->
                <div class="bg-white rounded-xl border border-slate-200/90 p-8 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-slate-900">Harian</h3>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-brand-50 text-brand-600 border border-brand-100">
                            Cocok untuk SD
                        </span>
                    </div>
                    <p class="text-sm text-slate-600 mb-6">
                        Satu kali absen per hari, dilakukan wali kelas untuk seluruh kelasnya.
                    </p>
                    <ul class="space-y-3 text-sm text-slate-700">
                        <li class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Absen sekali saat gerbang dibuka</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Wali kelas pegang penuh kelasnya</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Rekap harian langsung per kelas</span>
                        </li>
                    </ul>
                </div>

                <!-- Card Mode Per Jam Pelajaran -->
                <div class="bg-white rounded-xl border border-slate-200/90 p-8 shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-slate-900">Per Jam Pelajaran</h3>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-brand-50 text-brand-600 border border-brand-100">
                            Cocok untuk SMP & SMA
                        </span>
                    </div>
                    <p class="text-sm text-slate-600 mb-6">
                        Absen tiap jam pelajaran, dilakukan guru mapel sesuai jadwalnya masing-masing.
                    </p>
                    <ul class="space-y-3 text-sm text-slate-700">
                        <li class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Jadwal kelas &times; mapel &times; guru &times; jam ke-</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Guru mapel absen kelasnya sendiri</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Rekap per mapel dan per jam pelajaran</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Absensi Guru & Staff Section -->
    <section class="py-16 sm:py-24 bg-slate-50/70 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-left mb-12">
                <div class="inline-flex items-center space-x-2 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-600">BARU · ABSENSI GURU & STAFF</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-brand-100 text-brand-700">Fitur Berbayar</span>
                </div>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900">
                    Bukan cuma siswa — guru dan staff pun absen di sistem yang sama
                </h2>
                <p class="text-sm sm:text-base text-slate-600 mt-2 max-w-2xl">
                    Seperti siswa, mereka bisa scan QR di gerbang — atau absen sendiri dari HP masing-masing kalau sedang di luar kelas.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Kiosk Gerbang -->
                <div class="bg-white rounded-xl border border-slate-200/90 p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-slate-900">Kiosk Gerbang</h3>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-brand-50 text-brand-600 border border-brand-100">
                            Sama seperti siswa
                        </span>
                    </div>
                    <p class="text-sm text-slate-600 mb-6">
                        Guru dan staff tinggal scan QR mereka sendiri di kiosk gerbang yang sama dengan siswa — tidak perlu alat tambahan.
                    </p>
                    <ul class="space-y-3 text-sm text-slate-700">
                        <li class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>QR unik untuk setiap guru dan staff</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Satu kiosk untuk siswa, guru, dan staff sekaligus</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Otomatis tercatat Hadir atau Terlambat sesuai jam masuk</span>
                        </li>
                    </ul>
                </div>

                <!-- Absen Mandiri HP -->
                <div class="bg-white rounded-xl border border-slate-200/90 p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-slate-900">Absen Mandiri</h3>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-brand-50 text-brand-600 border border-brand-100">
                            Dari HP sendiri
                        </span>
                    </div>
                    <p class="text-sm text-slate-600 mb-6">
                        Guru atau staff yang tidak lewat gerbang tetap bisa absen sendiri — foto selfie dan lokasi terekam otomatis dari HP mereka.
                    </p>
                    <ul class="space-y-3 text-sm text-slate-700">
                        <li class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Selfie langsung dari kamera HP, bukan face recognition</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Lokasi GPS terekam otomatis saat absen masuk & pulang</span>
                        </li>
                        <li class="flex items-center space-x-2.5">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Riwayat foto dan lokasi bisa dicek admin kapan saja</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Kenapa Bisa Dipercaya Section -->
    <section class="py-16 sm:py-24 bg-white border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-left mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600">KENAPA BISA DIPERCAYA</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 mt-2">
                    Aman, transparan, tidak mengunci Anda
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Item 1 -->
                <div class="bg-slate-50/80 rounded-2xl p-6 border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base mb-2">Data terisolasi penuh per sekolah</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Setiap sekolah punya ruang data sendiri. Sekolah lain di sistem yang sama tidak bisa mengakses data Anda, dan sebaliknya.
                    </p>
                </div>

                <!-- Item 2 -->
                <div class="bg-slate-50/80 rounded-2xl p-6 border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base mb-2">Setiap perubahan tercatat</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Koreksi status kehadiran tersimpan di audit log — jelas siapa mengubah apa dan kapan, bisa ditelusuri kapan saja.
                    </p>
                </div>

                <!-- Item 3 -->
                <div class="bg-slate-50/80 rounded-2xl p-6 border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base mb-2">Dukungan langsung ke tim kami</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Bukan tiket otomatis yang menunggu berhari-hari — pertanyaan Anda dijawab langsung oleh tim yang membangun sistem ini.
                    </p>
                </div>

                <!-- Item 4 -->
                <div class="bg-slate-50/80 rounded-2xl p-6 border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base mb-2">Data Anda, bisa dibawa kapan saja</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Rekap dan data siswa bisa diekspor ke Excel atau PDF kapan pun — tidak terkunci di satu sistem.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-16 sm:py-24 bg-slate-50/70 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-left mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600">HARGA</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 mt-2">
                    Mulai gratis, upgrade kalau perlu
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
                <!-- Paket Gratis -->
                <div class="bg-white rounded-xl border border-slate-200/90 p-8 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-sm font-bold text-slate-500">Gratis</span>
                        <div class="mt-2 mb-1">
                            <span class="text-3xl font-extrabold text-slate-900">Rp0</span>
                            <span class="text-xs text-slate-500">/selamanya</span>
                        </div>
                        <p class="text-xs text-slate-500 mb-6">Pencatatan kehadiran manual dengan batas jumlah siswa.</p>

                        <ul class="space-y-3 text-xs text-slate-700">
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Absen, sampai 100 siswa</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Rekap, dashboard, dan portal orang tua</span></li>
                            <li class="flex items-center space-x-2 text-slate-400"><svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span>Absensi QR & RFID</span></li>
                            <li class="flex items-center space-x-2 text-slate-400"><svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span>Presensi per mata pelajaran</span></li>
                            <li class="flex items-center space-x-2 text-slate-400"><svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span>Nilai & Rapor</span></li>
                            <li class="flex items-center space-x-2 text-slate-400"><svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span>Export rekap Excel & PDF</span></li>
                            <li class="flex items-center space-x-2 text-slate-400"><svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span>Absensi guru & staff</span></li>
                            <li class="flex items-center space-x-2 text-slate-400"><svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span>Keuangan & SPP</span></li>
                        </ul>
                    </div>

                    <a href="{{ route('login') }}" class="w-full mt-8 py-2.5 text-center text-xs font-semibold rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 transition block">
                        Daftarkan Sekolah
                    </a>
                </div>

                <!-- Paket Pro (Highlight Biru OnHadir) -->
                <div class="bg-brand-600 rounded-xl p-8 shadow-xl text-white flex flex-col justify-between relative transform lg:-translate-y-2">
                    <div>
                        <span class="text-sm font-bold text-white/80">Pro</span>
                        <div class="mt-2 mb-1">
                            <span class="text-3xl font-extrabold text-white">Rp550.000</span>
                            <span class="text-xs text-white/70">/tahun ajaran</span>
                        </div>
                        <p class="text-xs text-white/70 mb-6">Beberapa fitur absensi terbuka. Absensi QR & RFID, Presensi Per Mata Pelajaran, Nilai dan Rapor, Portal Orang Tua dan lainnya.</p>

                        <ul class="space-y-3 text-xs text-white">
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Absen, siswa tanpa batas</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Rekap, dashboard, dan portal orang tua</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Absensi QR & RFID</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Presensi per mata pelajaran</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Nilai & Rapor</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Export rekap Excel & PDF</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Portal orang tua</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Absensi guru & staff</span></li>
                            <li class="flex items-center space-x-2 text-white/50"><svg class="w-4 h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg><span>Keuangan & SPP</span></li>
                        </ul>
                    </div>

                    <a href="{{ route('login') }}" class="w-full mt-8 py-2.5 text-center text-xs font-semibold rounded-xl bg-white text-brand-600 hover:bg-brand-50 transition shadow-md block">
                        Daftarkan Sekolah
                    </a>
                </div>

                <!-- Paket Lengkap -->
                <div class="bg-white rounded-xl border border-slate-200/90 p-8 shadow-sm flex flex-col justify-between">
                    <div>
                        <span class="text-sm font-bold text-slate-500">Lengkap</span>
                        <div class="mt-2 mb-1">
                            <span class="text-3xl font-extrabold text-slate-900">Rp850.000</span>
                            <span class="text-xs text-slate-500">/tahun ajaran</span>
                        </div>
                        <p class="text-xs text-slate-500 mb-6">Pro + Keuangan & SPP, dan nanti PPDB.</p>

                        <ul class="space-y-3 text-xs text-slate-700">
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Absen, siswa tanpa batas</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Rekap, dashboard, dan portal orang tua</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Absensi QR & RFID</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Presensi per mata pelajaran</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Nilai & Rapor</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Export rekap Excel & PDF</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Portal orang tua</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Absensi guru & staff</span></li>
                            <li class="flex items-center space-x-2"><svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span>Keuangan & SPP</span></li>
                        </ul>
                    </div>

                    <a href="{{ route('login') }}" class="w-full mt-8 py-2.5 text-center text-xs font-semibold rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 transition block">
                        Daftarkan Sekolah
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 sm:py-24 bg-white border-t border-slate-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-left mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600">FAQ</span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 mt-2">
                    Pertanyaan yang sering ditanyakan
                </h2>
            </div>

            <div class="space-y-3" id="faqAccordion">
                <!-- FAQ 1 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <button type="button" onclick="toggleFaq(1)" class="w-full px-5 py-4 text-left font-bold text-sm text-slate-800 flex justify-between items-center hover:bg-slate-50 transition">
                        <span>Apakah data sekolah kami aman dan terpisah dari sekolah lain?</span>
                        <svg id="faqIcon-1" class="w-5 h-5 text-slate-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="faqAnswer-1" class="hidden px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Sangat aman. Setiap sekolah beroperasi di bawah database yang terisolasi dan dipisahkan secara logika. Data sekolah Anda tidak akan pernah bocor atau tercampur dengan sekolah lain.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <button type="button" onclick="toggleFaq(2)" class="w-full px-5 py-4 text-left font-bold text-sm text-slate-800 flex justify-between items-center hover:bg-slate-50 transition">
                        <span>Perlu perangkat scan QR khusus untuk mulai pakai?</span>
                        <svg id="faqIcon-2" class="w-5 h-5 text-slate-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="faqAnswer-2" class="hidden px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Tidak perlu. Anda bisa menggunakan kamera HP guru, kamera webcam laptop, atau scanner barcode USB standar yang murah.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <button type="button" onclick="toggleFaq(3)" class="w-full px-5 py-4 text-left font-bold text-sm text-slate-800 flex justify-between items-center hover:bg-slate-50 transition">
                        <span>Bisa pakai kartu RFID, bukan cuma QR?</span>
                        <svg id="faqIcon-3" class="w-5 h-5 text-slate-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="faqAnswer-3" class="hidden px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Bisa. OnHadir mendukung pembaca kartu RFID (Mifare 13.56MHz atau EM4100 125kHz) yang dicolokkan via USB ke laptop/komputer gerbang.
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <button type="button" onclick="toggleFaq(4)" class="w-full px-5 py-4 text-left font-bold text-sm text-slate-800 flex justify-between items-center hover:bg-slate-50 transition">
                        <span>Bagaimana kalau koneksi internet sekolah tidak stabil?</span>
                        <svg id="faqIcon-4" class="w-5 h-5 text-slate-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="faqAnswer-4" class="hidden px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Sistem dirancang ringan dan hemat kuota data. Saat scan di gerbang, data bisa disimpan secara offline sementara dan disinkronkan otomatis begitu jaringan kembali terhubung.
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden">
                    <button type="button" onclick="toggleFaq(5)" class="w-full px-5 py-4 text-left font-bold text-sm text-slate-800 flex justify-between items-center hover:bg-slate-50 transition">
                        <span>Kami masih pakai buku/kertas, bagaimana pindahnya?</span>
                        <svg id="faqIcon-5" class="w-5 h-5 text-slate-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="faqAnswer-5" class="hidden px-5 pb-4 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Sangat mudah! Anda cukup mengunggah data siswa dan kelas via file Excel / CSV yang sudah kami sediakan templatenya. Dalam hitungan menit seluruh siswa sudah siap dicatat.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pre-Footer Banner Biru Besar OnHadir -->
    <section class="py-16 sm:py-20 bg-brand-600 text-white text-center">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight">
                Coba dari kelas hari ini, bukan dari rapat.
            </h2>
            <p class="text-sm sm:text-base text-white/80 max-w-xl mx-auto">
                Daftar sekolah Anda dalam dua menit, langsung bisa dipakai.
            </p>
            <div class="pt-4">
                <a href="{{ route('login') }}" class="inline-flex items-center px-7 py-3.5 rounded-xl bg-white text-brand-600 hover:bg-brand-50 font-bold text-sm shadow-xl transition">
                    <span>Daftarkan Sekolah — Gratis</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-2">
                <div class="w-7 h-7 rounded-lg bg-brand-600 flex items-center justify-center text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <span class="font-bold text-slate-800 text-base">OnHadir</span>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-6 text-xs text-slate-500 font-medium">
                <a href="{{ route('login') }}" class="hover:text-slate-900 transition">Masuk</a>
                <a href="{{ route('login') }}" class="hover:text-slate-900 transition">Daftarkan Sekolah</a>
                <a href="#" class="hover:text-slate-900 transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-slate-900 transition">Syarat Layanan</a>
            </div>

            <p class="text-xs text-slate-400">
                &copy; {{ date('Y') }} PT Onlenkan Teknologi Indonesia
            </p>
        </div>
    </footer>

    <!-- WhatsApp Floating Action Button (Kanan Bawah) -->
    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="fixed bottom-6 right-6 z-50 w-14 h-14 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white flex items-center justify-center shadow-xl hover:scale-105 transition duration-200" title="Hubungi CS OnHadir">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12.031 2C6.49 2 2 6.48 2 12.016c0 1.95.556 3.824 1.606 5.438L2.016 22l4.693-1.554a9.96 9.96 0 005.322 1.556h.005C17.564 22 22.05 17.52 22.05 11.984 22.05 6.448 17.564 2 12.031 2zm5.787 14.288c-.244.686-1.42 1.32-1.95 1.39-.508.068-1.157.102-3.72-1.002-3.11-1.33-5.09-4.526-5.244-4.736-.154-.21-1.258-1.674-1.258-3.193 0-1.518.796-2.267 1.08-2.57.284-.303.62-.378.827-.378.207 0 .414.002.593.01.19.01.442-.072.693.53.257.618.88 2.146.957 2.302.078.156.13.338.026.544-.103.207-.156.336-.31.517-.155.18-.328.403-.467.54-.156.155-.318.324-.136.637.181.313.805 1.327 1.726 2.147 1.185 1.054 2.183 1.381 2.494 1.536.31.155.492.13.673-.077.182-.207.777-.905.984-1.215.207-.31.414-.259.697-.155.284.103 1.802.85 2.112 1.005.31.155.518.232.594.362.077.13.077.75-.167 1.436z"/>
        </svg>
    </a>

    <!-- FAQ Toggle Script -->
    <script>
        function toggleFaq(id) {
            const ans = document.getElementById('faqAnswer-' + id);
            const icon = document.getElementById('faqIcon-' + id);
            if (!ans || !icon) return;
            ans.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
    </script>
</body>
</html>
