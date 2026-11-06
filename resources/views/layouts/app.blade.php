<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Dashboard') — SMK Darus Sa'adah</title>
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
                        },
                        gold: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    }
                }
            }
        }
    </script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        /* Custom scrollbar khusus sidebar */
        #sidebarNavContainer::-webkit-scrollbar {
            width: 4px;
        }
        #sidebarNavContainer::-webkit-scrollbar-track {
            background: transparent;
        }
        #sidebarNavContainer::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 4px;
        }
        #sidebarNavContainer::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.25);
        }
        @media print {
            .no-print, header, nav, aside, #mainSidebar, .no-print * {
                display: none !important;
            }
            body, html {
                background-color: #ffffff !important;
                height: auto !important;
                overflow: visible !important;
            }
            main {
                overflow: visible !important;
                padding: 0 !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="text-slate-800 antialiased h-full h-[100dvh] overflow-hidden flex flex-col bg-slate-50 select-text">

    <div class="flex flex-1 h-full w-full overflow-hidden relative">
        <!-- Backdrop Overlay untuk Mobile (Sentuh luar sidebar untuk menutup) -->
        <div id="sidebarBackdrop" onclick="toggleSidebar(false)" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs z-40 hidden md:hidden transition-opacity duration-300"></div>

        <!-- Sidebar Navigation (Desktop: Static full height; Mobile: Sliding Drawer 100dvh) -->
        <aside id="mainSidebar" class="fixed inset-y-0 left-0 z-50 w-64 md:w-64 h-full h-[100dvh] bg-slate-900 border-r border-slate-800 text-slate-300 flex flex-col shrink-0 no-print transform -translate-x-full md:translate-x-0 md:static transition-transform duration-300 ease-in-out shadow-2xl md:shadow-none">
            
            <!-- School Brand Header -->
            <div class="p-3.5 border-b border-slate-800/80 shrink-0">
                @php $navSchoolSetting = \App\Models\SchoolSetting::get(); @endphp
                <div class="flex items-center justify-between">
                    <a href="{{ route('dashboard') }}" class="w-full flex items-center justify-between px-2.5 py-2 rounded-xl bg-slate-800/60 hover:bg-slate-800 text-slate-200 border border-slate-700/60 transition group">
                        <div class="flex items-center space-x-2.5 overflow-hidden">
                            <div class="w-9 h-9 rounded-lg bg-emerald-950/90 border border-emerald-500/40 flex items-center justify-center shrink-0 p-1 shadow-sm">
                                <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Darus Sa'adah" class="w-full h-full object-contain">
                            </div>
                            <div class="overflow-hidden">
                                <h2 class="text-xs font-bold text-white truncate leading-tight tracking-tight">SMK DARUS SA'ADAH</h2>
                                <p class="text-[10px] text-emerald-400 font-medium truncate">Presensi & Disiplin Siswa</p>
                            </div>
                        </div>
                    </a>

                    <!-- Tombol Tutup Sidebar di Mobile -->
                    <button type="button" onclick="toggleSidebar(false)" class="md:hidden ml-2 p-1.5 text-slate-400 hover:text-white rounded-lg transition" title="Tutup Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Main Navigation Links (Independent Scroll) -->
            <div id="sidebarNavContainer" class="flex-1 px-3 py-3 space-y-4 overflow-y-auto overscroll-contain">
                <!-- Group: UTAMA -->
                <div class="space-y-1">
                    <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">UTAMA</p>
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white font-semibold border border-slate-700/80 shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 mr-2.5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>

                <!-- Group: DATA SEKOLAH -->
                <div class="space-y-1">
                    <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">DATA SEKOLAH</p>
                    <a href="{{ route('academic-years.index') }}" class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('academic-years.*') ? 'bg-slate-800 text-white font-semibold border border-slate-700/80 shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 mr-2.5 shrink-0 {{ request()->routeIs('academic-years.*') ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Tahun Ajaran</span>
                    </a>
                    <a href="{{ route('users.index') }}" class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('users.*') ? 'bg-slate-800 text-white font-semibold border border-slate-700/80 shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 mr-2.5 shrink-0 {{ request()->routeIs('users.*') ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Guru & Pegawai</span>
                    </a>
                    <a href="{{ route('classrooms.index') }}" class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('classrooms.*') ? 'bg-slate-800 text-white font-semibold border border-slate-700/80 shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 mr-2.5 shrink-0 {{ request()->routeIs('classrooms.*') ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                        <span>Kelas (Rombel)</span>
                    </a>
                    <a href="{{ route('students.index') }}" class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('students.index', 'students.show', 'students.edit', 'students.create') ? 'bg-slate-800 text-white font-semibold border border-slate-700/80 shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 mr-2.5 shrink-0 {{ request()->routeIs('students.index', 'students.show', 'students.edit', 'students.create') ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Data Siswa</span>
                    </a>
                    <a href="{{ route('students.cards') }}" class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('students.cards') ? 'bg-slate-800 text-white font-semibold border border-slate-700/80 shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 mr-2.5 shrink-0 {{ request()->routeIs('students.cards') ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>
                        <span>Cetak Kartu Pelajar</span>
                    </a>
                    @if(auth()->user()?->isAdmin())
                    <a href="{{ route('students.batch-create') }}" class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('students.batch-create') ? 'bg-slate-800 text-white font-semibold border border-slate-700/80 shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 mr-2.5 shrink-0 {{ request()->routeIs('students.batch-create') ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <span>Input Siswa Massal</span>
                    </a>
                    @endif
                </div>

                <!-- Group: PRESENSI -->
                <div class="space-y-1">
                    <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">PRESENSI</p>
                    <a href="{{ route('attendance.scanner') }}" class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('attendance.scanner') ? 'bg-slate-800 text-white font-semibold border border-slate-700/80 shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 mr-2.5 shrink-0 {{ request()->routeIs('attendance.scanner') ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                        <div class="flex items-center justify-between w-full">
                            <span>Scan QR Presensi</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        </div>
                    </a>
                    <a href="{{ route('attendance.create') }}" class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('attendance.create') ? 'bg-slate-800 text-white font-semibold border border-slate-700/80 shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 mr-2.5 shrink-0 {{ request()->routeIs('attendance.create') ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Input Absensi Baru</span>
                    </a>
                    <a href="{{ route('attendance.index') }}" class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('attendance.index', 'attendance.show', 'attendance.edit') ? 'bg-slate-800 text-white font-semibold border border-slate-700/80 shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 mr-2.5 shrink-0 {{ request()->routeIs('attendance.index', 'attendance.show', 'attendance.edit') ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span>Riwayat Sesi Absensi</span>
                    </a>
                </div>

                <!-- Group: REKAPITULASI & LAPORAN -->
                <div class="space-y-1">
                    <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">REKAPITULASI</p>
                    <a href="{{ route('recap.student') }}" class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('recap.student') ? 'bg-slate-800 text-white font-semibold border border-slate-700/80 shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 mr-2.5 shrink-0 {{ request()->routeIs('recap.student') ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Rekap per Siswa</span>
                    </a>
                    <a href="{{ route('recap.class') }}" class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('recap.class') ? 'bg-slate-800 text-white font-semibold border border-slate-700/80 shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 mr-2.5 shrink-0 {{ request()->routeIs('recap.class') ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Rekap per Rombel</span>
                    </a>
                    <a href="{{ route('reports.index') }}" class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('reports.*') ? 'bg-slate-800 text-white font-semibold border border-slate-700/80 shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 mr-2.5 shrink-0 {{ request()->routeIs('reports.*') ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Laporan & Cetak</span>
                    </a>
                    <a href="{{ route('public.check') }}" target="_blank" class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition text-slate-400 hover:text-slate-200 hover:bg-slate-800/60">
                        <svg class="w-4 h-4 mr-2.5 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        <div class="flex items-center justify-between w-full">
                            <span>Portal Wali Murid</span>
                            <span class="text-[9px] font-semibold bg-emerald-500/20 text-emerald-400 px-1.5 py-0.5 rounded">Publik</span>
                        </div>
                    </a>
                </div>

                @if(auth()->user()?->isAdmin())
                <!-- Group: SISTEM -->
                <div class="space-y-1">
                    <p class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">SISTEM</p>
                    <a href="{{ route('settings.index') }}" class="flex items-center px-3 py-2 rounded-lg text-xs font-medium transition {{ request()->routeIs('settings.*') ? 'bg-slate-800 text-white font-semibold border border-slate-700/80 shadow-xs' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}">
                        <svg class="w-4 h-4 mr-2.5 shrink-0 {{ request()->routeIs('settings.*') ? 'text-emerald-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Pengaturan Sekolah</span>
                    </a>
                </div>
                @endif
            </div>

            <!-- User Footer Info & Profile Dropup (Anchored at Bottom, Never Disappears) -->
            <div class="relative p-3 border-t border-slate-800 bg-slate-900 shrink-0">
                <!-- Floating Profile Popover -->
                <div id="userProfilePopover" class="hidden absolute bottom-full left-3 right-3 mb-2 bg-slate-900 rounded-xl shadow-2xl border border-slate-800 p-2 text-slate-200 z-50 transition-all">
                    <div class="flex items-center space-x-2.5 p-2 border-b border-slate-800">
                        <div class="w-8 h-8 rounded-lg bg-emerald-950 border border-emerald-700/60 flex items-center justify-center font-bold text-xs text-emerald-300 shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="font-semibold text-xs text-white truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                            <p class="text-[10px] text-slate-400 truncate">{{ auth()->user()->email ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="py-1">
                        <a href="{{ auth()->user()?->isAdmin() ? route('settings.index') : route('dashboard') }}" class="flex items-center space-x-2 px-2.5 py-1.5 rounded-md text-xs font-medium text-slate-300 hover:bg-slate-800 transition">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Pengaturan Akun</span>
                        </a>
                    </div>
                    <div class="border-t border-slate-800 pt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-2 px-2.5 py-1.5 rounded-md text-xs font-semibold text-rose-400 hover:bg-rose-950/40 transition">
                                <svg class="w-3.5 h-3.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Keluar (Logout)</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Bottom Profile Trigger Button -->
                <button type="button" onclick="toggleProfilePopover()" class="w-full flex items-center justify-between p-2 rounded-xl bg-slate-800/40 hover:bg-slate-800 text-slate-200 border border-slate-800 transition">
                    <div class="flex items-center space-x-2.5 overflow-hidden">
                        <div class="w-7 h-7 rounded-lg bg-emerald-950 border border-emerald-600/50 flex items-center justify-center font-bold text-xs text-emerald-300 shrink-0">
                            {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                        </div>
                        <div class="text-left overflow-hidden">
                            <p class="text-xs font-semibold text-slate-200 truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
                            <p class="text-[10px] text-slate-400 capitalize truncate">{{ auth()->user()->role ?? 'Admin' }}</p>
                        </div>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                    </svg>
                </button>
            </div>
        </aside>

        <!-- Main Content Area (Independent Scroll Container) -->
        <div class="flex-1 flex flex-col h-full min-w-0 overflow-hidden bg-slate-50">
            <!-- Topbar Header -->
            <header class="h-14 bg-white border-b border-slate-200/90 px-4 sm:px-6 flex items-center justify-between shrink-0 no-print z-20">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <!-- Hamburger button for Mobile -->
                    <button type="button" onclick="toggleSidebar(true)" class="md:hidden p-2 -ml-1 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition" title="Buka Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Mobile Title & Logo -->
                    <div class="flex items-center space-x-2 md:hidden overflow-hidden">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-6 h-6 object-contain shrink-0">
                        <span class="text-xs font-bold text-slate-900 truncate">SMK Darus Sa'adah</span>
                    </div>

                    <!-- Breadcrumb Navigation for Desktop / Laptop -->
                    <div class="hidden md:flex items-center space-x-2 text-xs text-slate-500 font-normal">
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        @yield('breadcrumbs')
                    </div>
                </div>

                <!-- Right Quick Stats & Actions -->
                <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
                    <span class="hidden lg:inline-flex items-center space-x-1.5 text-[11px] px-2.5 py-1 rounded-md bg-slate-50 text-slate-600 font-medium border border-slate-200">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</span>
                    </span>
                    <span class="hidden sm:inline-flex items-center space-x-1.5 text-[11px] px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 font-semibold border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>{{ \App\Models\AcademicYear::where('is_active', true)->value('year_name') ?? 'T.A. Aktif' }}</span>
                    </span>
                    <a href="{{ route('attendance.create') }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition flex items-center space-x-1.5">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>+ Presensi</span>
                    </a>
                </div>
            </header>

            <!-- Page Body Area: Scrolls independently on laptop / mobile -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 overscroll-contain">
                <!-- Flash Alerts -->
                @if(session('success'))
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start space-x-2.5 text-xs font-medium shadow-xs">
                    <svg class="w-4 h-4 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>{{ session('success') }}</div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start space-x-2.5 text-xs font-medium shadow-xs">
                    <svg class="w-4 h-4 text-rose-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>{{ session('error') }}</div>
                </div>
                @endif

                @if(isset($errors) && $errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-xs">
                    <div class="flex items-center space-x-2 font-semibold text-xs mb-1">
                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>Perhatikan kesalahan input berikut:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-700">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar(open) {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (!sidebar || !backdrop) return;

            if (open) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        function toggleProfilePopover() {
            const popover = document.getElementById('userProfilePopover');
            if (popover) {
                popover.classList.toggle('hidden');
            }
        }

        // Close popover when clicking outside
        document.addEventListener('click', function(event) {
            const popover = document.getElementById('userProfilePopover');
            const trigger = event.target.closest('button[onclick="toggleProfilePopover()"]');
            if (popover && !popover.contains(event.target) && !trigger && !popover.classList.contains('hidden')) {
                popover.classList.add('hidden');
            }
        });

        // Preservasi Posisi Scroll Sidebar agar tidak mental ke atas saat reload / pindah menu
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarScroll = document.getElementById('sidebarNavContainer');
            if (!sidebarScroll) return;

            const savedScroll = sessionStorage.getItem('sidebar_scroll_pos');
            if (savedScroll !== null) {
                sidebarScroll.scrollTop = parseInt(savedScroll, 10);
            }

            const activeLink = sidebarScroll.querySelector('a.bg-slate-800');
            if (activeLink) {
                const rect = activeLink.getBoundingClientRect();
                const containerRect = sidebarScroll.getBoundingClientRect();
                if (rect.top < containerRect.top || rect.bottom > containerRect.bottom) {
                    activeLink.scrollIntoView({ block: 'nearest', behavior: 'instant' });
                }
            }

            sidebarScroll.addEventListener('scroll', function() {
                sessionStorage.setItem('sidebar_scroll_pos', sidebarScroll.scrollTop);
            }, { passive: true });

            sidebarScroll.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    sessionStorage.setItem('sidebar_scroll_pos', sidebarScroll.scrollTop);
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>