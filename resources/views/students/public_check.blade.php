<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Presensi & Kedisiplinan Siswa — SMK Darus Sa'adah</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
                            600: '#059669', // Emerald SMK Darus Sa'adah
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Top Navigation Header (Khusus Portal Orang Tua, Tanpa Tombol Dashboard Guru) -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 p-1 flex items-center justify-center shadow-xs">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Darus Sa'adah" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="text-base font-extrabold text-slate-900 leading-tight">SMK DARUS SA'ADAH</h1>
                    <p class="text-xs text-emerald-700 font-semibold">Portal Presensi & Kedisiplinan Siswa</p>
                </div>
            </div>

            <!-- Status Portal Resmi & Informasi Kontak -->
            <div class="flex items-center space-x-3">
                <div class="flex items-center text-xs text-slate-600">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 font-semibold">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Portal Resmi Orang Tua & Siswa
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Search Hero Section (Elegan & Bernuansa Islami / Hijau SMK Darus Sa'adah) -->
        <div class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-950 rounded-2xl p-6 sm:p-8 text-white shadow-sm mb-8 border border-emerald-800/80">
            <div class="max-w-2xl">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-700/60 text-emerald-100 border border-emerald-600/40 mb-3">
                    Informasi Terpadu Orang Tua & Wali Murid
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Cek Presensi & Kedisiplinan Siswa
                </h2>
                <p class="text-emerald-100/90 text-xs sm:text-sm mt-2 leading-relaxed">
                    Pantau kehadiran harian, ketepatan waktu jam masuk, serta catatan pembinaan siswa secara transparan, akurat, dan real-time.
                </p>

                <!-- Formulir Pencarian NIS / NISN -->
                <form action="{{ route('public.check') }}" method="GET" class="mt-6">
                    <div class="flex flex-col sm:flex-row gap-2 bg-white/10 p-1.5 rounded-xl border border-white/20">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" 
                                   name="nis" 
                                   value="{{ request('nis') }}" 
                                   required 
                                   placeholder="Ketik NIS atau NISN siswa (contoh: 1023401)..."
                                   class="w-full pl-10 pr-4 py-2.5 bg-white text-slate-800 placeholder-slate-400 rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-400">
                        </div>
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-lg shadow-xs transition duration-150">
                            <span>Periksa Data</span>
                            <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(request('nis'))
            @if($student)
                <!-- Student Detail Header Card -->
                <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-slate-100">
                        <div class="flex items-start sm:items-center space-x-4">
                            <div class="w-14 h-16 rounded-xl border border-slate-200 bg-white overflow-hidden shrink-0 shadow-xs flex items-center justify-center">
                                @if($student->photo && file_exists(public_path($student->photo)))
                                    <img src="{{ asset($student->photo) }}" alt="{{ $student->name }}" class="w-full h-full object-cover object-top">
                                @else
                                    <div class="w-full h-full bg-emerald-700 text-white flex items-center justify-center text-xl font-bold">
                                        {{ strtoupper(substr($student->name, 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-xl font-bold text-slate-900">{{ $student->name }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $student->status === 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </div>
                                <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs text-slate-500 mt-1.5 font-medium">
                                    <span>NIS: <strong class="text-slate-800 font-semibold">{{ $student->nis }}</strong></span>
                                    @if($student->nisn)
                                        <span>&bull; NISN: <strong class="text-slate-800 font-semibold">{{ $student->nisn }}</strong></span>
                                    @endif
                                    <span>&bull; Kelas: <strong class="text-emerald-700 font-semibold">{{ $student->classroom->name }}</strong></span>
                                    <span>&bull; Wali Kelas: <strong class="text-slate-800 font-semibold">{{ $student->classroom->waliKelas->name ?? '-' }}</strong></span>
                                </div>
                            </div>
                        </div>

                        <!-- Status Kedisiplinan BK -->
                        <div class="flex flex-col items-start md:items-end">
                            <span class="text-xs font-medium text-slate-500 mb-1">Status Kedisiplinan BK</span>
                            @php
                                $poin = $stats['totalPoin'] ?? 0;
                            @endphp
                            @if($poin == 0)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    <svg class="w-4 h-4 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    0 Poin &bull; Sangat Baik / Teladan
                                </span>
                            @elseif($poin < 25)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    <svg class="w-4 h-4 mr-1 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $poin }} Poin &bull; Perhatian Ringan
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-50 text-rose-800 border border-rose-200">
                                    <svg class="w-4 h-4 mr-1 text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $poin }} Poin &bull; Perlu Pembinaan / Konseling
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Statistics Matrix (Tampilan Bersih & Rapi Tanpa Warna Pelangi Norak) -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 pt-6">
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-center">
                            <span class="block text-2xl font-extrabold text-emerald-700">{{ $stats['persentase'] }}%</span>
                            <span class="text-xs font-semibold text-slate-600 mt-0.5 block">Tingkat Kehadiran</span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-center">
                            <span class="block text-2xl font-extrabold text-slate-900">{{ $stats['totalHadir'] }}</span>
                            <span class="text-xs font-semibold text-emerald-700 mt-0.5 flex items-center justify-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Tepat Waktu
                            </span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-center">
                            <span class="block text-2xl font-extrabold text-slate-900">{{ $stats['totalTerlambat'] }}</span>
                            <span class="text-xs font-semibold text-amber-700 mt-0.5 flex items-center justify-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                Terlambat
                            </span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-center">
                            <span class="block text-2xl font-extrabold text-slate-900">{{ $stats['totalSakit'] }}</span>
                            <span class="text-xs font-semibold text-yellow-700 mt-0.5 flex items-center justify-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                Sakit
                            </span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-center">
                            <span class="block text-2xl font-extrabold text-slate-900">{{ $stats['totalIzin'] }}</span>
                            <span class="text-xs font-semibold text-sky-700 mt-0.5 flex items-center justify-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                                Izin
                            </span>
                        </div>
                        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-center">
                            <span class="block text-2xl font-extrabold text-slate-900">{{ $stats['totalAlpa'] }}</span>
                            <span class="text-xs font-semibold text-rose-700 mt-0.5 flex items-center justify-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                Alpa
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Timeline Section: Riwayat 10 Hari Terakhir -->
                <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs p-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                        <div>
                            <h4 class="text-base font-bold text-slate-900">Riwayat Presensi & Catatan Kedisiplinan</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Daftar rekaman kehadiran harian dan tindak lanjut tugas edukatif siswa.</p>
                        </div>
                        <span class="text-xs font-medium text-slate-600 bg-slate-100 px-3 py-1 rounded-full border border-slate-200">
                            10 Sesi Terakhir
                        </span>
                    </div>

                    @if($attendances->count() > 0)
                        <div class="relative pl-6 sm:pl-8 border-l-2 border-slate-200 space-y-6 ml-2">
                            @foreach($attendances as $item)
                                @php
                                    $sessionDate = $item->attendanceSession ? $item->attendanceSession->attendance_date->translatedFormat('l, d F Y') : '-';
                                    $isLate = ($item->status === 'T');
                                    $isAlpha = ($item->status === 'A');
                                    $isNormal = ($item->status === 'H');
                                    $isSakit = ($item->status === 'S');
                                    $isIzin = ($item->status === 'I');
                                @endphp
                                <div class="relative group">
                                    <!-- Timeline Node Circle -->
                                    <div class="absolute -left-[31px] sm:-left-[39px] top-1.5 w-5 h-5 rounded-full border-4 border-white shadow-xs flex items-center justify-center 
                                        {{ $isLate ? 'bg-amber-500 ring-2 ring-amber-200' : ($isAlpha ? 'bg-rose-500 ring-2 ring-rose-200' : ($isNormal ? 'bg-emerald-500 ring-2 ring-emerald-200' : ($isSakit ? 'bg-yellow-500 ring-2 ring-yellow-200' : 'bg-sky-500 ring-2 ring-sky-200'))) }}">
                                    </div>

                                    <!-- Content Card -->
                                    <div class="bg-slate-50/70 hover:bg-slate-100/80 transition-colors border border-slate-200/80 rounded-xl p-4 sm:p-5">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-200/60">
                                            <div>
                                                <span class="text-xs font-bold text-slate-800 tracking-wide uppercase">{{ $sessionDate }}</span>
                                                <div class="flex items-center space-x-2 mt-0.5 text-xs text-slate-500">
                                                    <span>Waktu Tap: <strong>{{ $item->scanned_at ? $item->scanned_at->format('H:i') . ' WIB' : '-' }}</strong></span>
                                                    @if($item->tap_out_at)
                                                        <span>&bull; Tap Pulang: <strong>{{ $item->tap_out_at->format('H:i') . ' WIB' }}</strong></span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div>
                                                @if($item->status === 'H')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                        ✓ Hadir Tepat Waktu
                                                    </span>
                                                @elseif($item->status === 'T')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                                        Terlambat {{ $item->late_minutes ?? 0 }} Menit
                                                    </span>
                                                @elseif($item->status === 'S')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                        Sakit
                                                    </span>
                                                @elseif($item->status === 'I')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-sky-100 text-sky-800 border border-sky-200">
                                                        Izin
                                                    </span>
                                                @elseif($item->status === 'A')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                                                        Alpa / Tanpa Keterangan
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Informasi Keterlambatan & Dampak Jam Pelajaran -->
                                        @if($isLate)
                                            <div class="mt-3 pt-2 text-xs space-y-2">
                                                @if(!empty($item->missed_periods))
                                                    <div class="flex flex-wrap items-center gap-1.5">
                                                        <span class="font-semibold text-slate-700">Jam Pelajaran Tertinggal:</span>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded bg-amber-100 text-amber-900 font-medium text-[11px] border border-amber-200">
                                                            {{ $item->missed_periods }}
                                                        </span>
                                                    </div>
                                                @endif

                                                @if($item->compensation_task)
                                                    <div class="bg-white border border-slate-200 rounded-lg p-3">
                                                        <div class="flex items-center justify-between">
                                                            <span class="font-semibold text-slate-800 flex items-center">
                                                                <svg class="w-4 h-4 mr-1 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                </svg>
                                                                Tugas Pembinaan / Kompensasi:
                                                            </span>
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold {{ $item->compensation_status === 'completed' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                                                {{ $item->compensation_status === 'completed' ? '✓ Sudah Diselesaikan' : '⏳ Dalam Pengerjaan' }}
                                                            </span>
                                                        </div>
                                                        <p class="text-slate-600 font-medium mt-1">{{ $item->compensation_task }}</p>
                                                    </div>
                                                @endif

                                                @if($item->discipline_points > 0)
                                                    <div class="text-[11px] text-slate-500 font-medium">
                                                        Poin Kedisiplinan: <strong class="text-rose-600">+{{ $item->discipline_points }} Poin</strong>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        @if($item->notes)
                                            <p class="text-xs text-slate-600 mt-2 bg-white p-2.5 rounded-lg border border-slate-200">
                                                <strong class="text-slate-700">Catatan Petugas:</strong> {{ $item->notes }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 text-slate-500">
                            <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-xs font-semibold text-slate-700">Belum Ada Riwayat Presensi</p>
                            <p class="text-xs text-slate-400 mt-1">Data absensi siswa belum tercatat pada pangkalan data sistem.</p>
                        </div>
                    @endif
                </div>
            @else
                <!-- Alert Data Tidak Ditemukan -->
                <div class="bg-white rounded-xl border border-rose-200 shadow-xs p-8 text-center max-w-lg mx-auto">
                    <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Data Siswa Tidak Ditemukan</h3>
                    <p class="text-xs text-slate-600 mt-1">
                        Siswa dengan NIS atau NISN <span class="font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">"{{ request('nis') }}"</span> tidak terdaftar dalam pangkalan data.
                    </p>
                    <p class="text-xs text-slate-400 mt-2">
                        Silakan periksa kembali nomor induk siswa atau hubungi bagian Tata Usaha / Wali Kelas sekolah.
                    </p>
                </div>
            @endif
        @else
            <!-- Default Info Cards before search (Bersih & Informatif) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl border border-slate-200/90 shadow-xs">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900">Pencatatan Presensi Real-Time</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Jam kedatangan siswa terekam otomatis saat melakukan scan barcode/QR code di gerbang masuk sekolah.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl border border-slate-200/90 shadow-xs">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900">Transparansi Keterlambatan</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Orang tua dapat mengetahui rincian jam pelajaran yang tertinggal serta tugas pembinaan yang diberikan kepada siswa.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl border border-slate-200/90 shadow-xs">
                    <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900">Pembinaan Kedisiplinan</h4>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                        Pencatatan poin kedisiplinan Buku Saku BK yang adil dan mendidik demi membentuk karakter integritas siswa.
                    </p>
                </div>
            </div>
        @endif

    </main>

    <!-- Footer Lengkap dengan Alamat Resmi Sekolah -->
    <footer class="bg-white border-t border-slate-200 py-6 mt-auto">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between text-xs text-slate-500 gap-3">
            <div>
                <p class="font-bold text-slate-800">{{ $schoolSetting->school_name ?? 'SMK DARUS SA\'ADAH' }}</p>
                <p class="text-[11px] text-slate-500 mt-0.5">
                    {{ $schoolSetting->school_address ?? 'Jl. Alternatif Sentul No. 38, RT. 06/RW. 07, Desa Cijujung, Kec. Sukaraja, Kab. Bogor, Jawa Barat 16710' }}
                </p>
            </div>
            <div class="text-center md:text-right text-[11px] text-slate-400">
                <p>&copy; {{ date('Y') }} SMK Darus Sa'adah. Seluruh hak cipta dilindungi.</p>
                <p class="text-emerald-700 font-medium">Sistem Presensi & Kedisiplinan Terintegrasi</p>
            </div>
        </div>
    </footer>

</body>
</html>
