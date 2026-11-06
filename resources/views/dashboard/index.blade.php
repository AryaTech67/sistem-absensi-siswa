@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumbs')
    <span class="font-medium text-slate-800">Dashboard</span>
@endsection

@section('content')
<div class="space-y-5">

    <!-- Header Greeting -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">SISTEM PRESENSI SISWA</span>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight mt-0.5">
                Selamat datang, {{ auth()->user()->name ?? 'Administrator' }}
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('attendance.create') }}" class="px-3.5 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-medium text-xs shadow-xs transition flex items-center space-x-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Catat Absensi</span>
            </a>
            <a href="{{ route('reports.index') }}" class="px-3.5 py-2 rounded-lg bg-white hover:bg-slate-50 text-slate-700 font-medium text-xs border border-slate-300 shadow-xs transition">
                Lihat Laporan
            </a>
        </div>
    </div>

    <!-- Setup Checklist Guide -->
    <div class="bg-white border border-slate-200/90 rounded-xl p-4 sm:p-5 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3.5 border-b border-slate-100">
            <div class="flex items-center space-x-2.5">
                <div class="w-7 h-7 rounded-md bg-emerald-600 text-white flex items-center justify-center shrink-0 text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-sm">Alur Konfigurasi Sekolah</h3>
                    <p class="text-xs text-slate-500">Panduan setup data pokok untuk memulai operasional presensi harian.</p>
                </div>
            </div>
            <span class="self-start sm:self-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                SIAP DIGUNAKAN
            </span>
        </div>

        <!-- 4 Step Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mt-3.5">
            <!-- Step 1 -->
            <div class="bg-slate-50/60 rounded-lg p-3.5 border border-slate-200 flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="w-4 h-4 rounded bg-slate-800 text-white text-[10px] font-bold flex items-center justify-center">1</span>
                        <h4 class="font-bold text-xs text-slate-900">Tahun Ajaran</h4>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                        Tentukan tahun ajaran & semester aktif saat ini.
                    </p>
                </div>
                <a href="{{ route('academic-years.index') }}" class="text-[11px] font-semibold text-slate-700 hover:text-slate-900 mt-2.5 inline-flex items-center">
                    <span>Kelola TA</span>
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Step 2 -->
            <div class="bg-slate-50/60 rounded-lg p-3.5 border border-slate-200 flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="w-4 h-4 rounded bg-slate-800 text-white text-[10px] font-bold flex items-center justify-center">2</span>
                        <h4 class="font-bold text-xs text-slate-900">Guru / Pegawai</h4>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                        Daftarkan guru yang bertindak sebagai wali kelas.
                    </p>
                </div>
                <a href="{{ route('users.index') }}" class="text-[11px] font-semibold text-slate-700 hover:text-slate-900 mt-2.5 inline-flex items-center">
                    <span>Data Guru</span>
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Step 3 -->
            <div class="bg-slate-50/60 rounded-lg p-3.5 border border-slate-200 flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="w-4 h-4 rounded bg-slate-800 text-white text-[10px] font-bold flex items-center justify-center">3</span>
                        <h4 class="font-bold text-xs text-slate-900">Kelas / Rombel</h4>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                        Susun rombel kelas dan tentukan wali kelasnya.
                    </p>
                </div>
                <a href="{{ route('classrooms.index') }}" class="text-[11px] font-semibold text-slate-700 hover:text-slate-900 mt-2.5 inline-flex items-center">
                    <span>Data Rombel</span>
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Step 4 -->
            <div class="bg-slate-50/60 rounded-lg p-3.5 border border-slate-200 flex flex-col justify-between">
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="w-4 h-4 rounded bg-slate-800 text-white text-[10px] font-bold flex items-center justify-center">4</span>
                        <h4 class="font-bold text-xs text-slate-900">Data Siswa</h4>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                        Input siswa atau import data massal via form.
                    </p>
                </div>
                <a href="{{ route('students.index') }}" class="text-[11px] font-semibold text-slate-700 hover:text-slate-900 mt-2.5 inline-flex items-center">
                    <span>Data Siswa</span>
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Grid 4 Kartu Statistik Utama -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
        <!-- Card 1: TOTAL SISWA -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">TOTAL SISWA</span>
                <div class="w-7 h-7 rounded-md bg-slate-100 text-slate-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline space-x-1.5">
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalStudents }}</span>
                    <span class="text-xs font-medium text-slate-500">siswa</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5">Terdaftar aktif</p>
            </div>
        </div>

        <!-- Card 2: TOTAL KELAS -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">TOTAL KELAS</span>
                <div class="w-7 h-7 rounded-md bg-slate-100 text-slate-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline space-x-1.5">
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalClassrooms }}</span>
                    <span class="text-xs font-medium text-slate-500">rombel</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5">Kelas aktif</p>
            </div>
        </div>

        <!-- Card 3: TOTAL GURU -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">TOTAL GURU</span>
                <div class="w-7 h-7 rounded-md bg-slate-100 text-slate-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline space-x-1.5">
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalUsers ?? 1 }}</span>
                    <span class="text-xs font-medium text-slate-500">guru</span>
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5">Tenaga pendidik</p>
            </div>
        </div>

        <!-- Card 4: TAHUN AJARAN -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">TAHUN AJARAN</span>
                <div class="w-7 h-7 rounded-md bg-slate-100 text-slate-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="flex items-baseline space-x-1.5">
                    <span class="text-lg sm:text-xl font-extrabold text-slate-900 truncate">
                        {{ $activeYear ? $activeYear->year_name : '-' }}
                    </span>
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5">Semester berjalan</p>
            </div>
        </div>
    </div>

    <!-- Ringkasan Bulan Ini (Status Kehadiran) -->
    <div class="bg-white rounded-xl p-5 border border-slate-200/90 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3.5 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-slate-900 text-sm">Rekapitulasi Kehadiran Hari Ini</h3>
                <p class="text-xs text-slate-400 mt-0.5 font-medium">
                    {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </p>
            </div>
            <span class="text-xs text-slate-500 font-medium">
                {{ $totalRecordedToday }} record diproses
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mt-4">
            <!-- HADIR -->
            <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200/80 flex flex-col justify-between">
                <span class="text-[10px] font-bold text-emerald-700 tracking-wider">HADIR</span>
                <div class="mt-1.5">
                    <span class="text-xl font-extrabold text-slate-900">{{ $hadirToday }}</span>
                    <p class="text-[11px] text-slate-500 mt-0.5 font-medium">
                        {{ $totalRecordedToday > 0 ? round(($hadirToday / $totalRecordedToday) * 100, 1) : 0 }}%
                    </p>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-1 mt-2.5">
                    <div class="bg-emerald-600 h-1 rounded-full" style="width: {{ $totalRecordedToday > 0 ? min(100, round(($hadirToday / $totalRecordedToday) * 100, 1)) : 0 }}%"></div>
                </div>
            </div>

            <!-- TERLAMBAT -->
            <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200/80 flex flex-col justify-between">
                <span class="text-[10px] font-bold text-amber-700 tracking-wider">TERLAMBAT</span>
                <div class="mt-1.5">
                    <span class="text-xl font-extrabold text-slate-900">{{ $terlambatToday ?? 0 }}</span>
                    <p class="text-[11px] text-slate-500 mt-0.5 font-medium">
                        {{ $totalRecordedToday > 0 ? round(($terlambatToday / $totalRecordedToday) * 100, 1) : 0 }}%
                    </p>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-1 mt-2.5">
                    <div class="bg-amber-500 h-1 rounded-full" style="width: {{ $totalRecordedToday > 0 ? min(100, round(($terlambatToday / $totalRecordedToday) * 100, 1)) : 0 }}%"></div>
                </div>
            </div>

            <!-- SAKIT -->
            <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200/80 flex flex-col justify-between">
                <span class="text-[10px] font-bold text-yellow-700 tracking-wider">SAKIT</span>
                <div class="mt-1.5">
                    <span class="text-xl font-extrabold text-slate-900">{{ $sakitToday }}</span>
                    <p class="text-[11px] text-slate-500 mt-0.5 font-medium">
                        {{ $totalRecordedToday > 0 ? round(($sakitToday / $totalRecordedToday) * 100, 1) : 0 }}%
                    </p>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-1 mt-2.5">
                    <div class="bg-yellow-500 h-1 rounded-full" style="width: {{ $totalRecordedToday > 0 ? min(100, round(($sakitToday / $totalRecordedToday) * 100, 1)) : 0 }}%"></div>
                </div>
            </div>

            <!-- IZIN -->
            <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200/80 flex flex-col justify-between">
                <span class="text-[10px] font-bold text-sky-700 tracking-wider">IZIN</span>
                <div class="mt-1.5">
                    <span class="text-xl font-extrabold text-slate-900">{{ $izinToday }}</span>
                    <p class="text-[11px] text-slate-500 mt-0.5 font-medium">
                        {{ $totalRecordedToday > 0 ? round(($izinToday / $totalRecordedToday) * 100, 1) : 0 }}%
                    </p>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-1 mt-2.5">
                    <div class="bg-sky-600 h-1 rounded-full" style="width: {{ $totalRecordedToday > 0 ? min(100, round(($izinToday / $totalRecordedToday) * 100, 1)) : 0 }}%"></div>
                </div>
            </div>

            <!-- ALPA -->
            <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200/80 flex flex-col justify-between col-span-2 sm:col-span-1">
                <span class="text-[10px] font-bold text-rose-700 tracking-wider">ALPA</span>
                <div class="mt-1.5">
                    <span class="text-xl font-extrabold text-slate-900">{{ $alpaToday }}</span>
                    <p class="text-[11px] text-slate-500 mt-0.5 font-medium">
                        {{ $totalRecordedToday > 0 ? round(($alpaToday / $totalRecordedToday) * 100, 1) : 0 }}%
                    </p>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-1 mt-2.5">
                    <div class="bg-rose-600 h-1 rounded-full" style="width: {{ $totalRecordedToday > 0 ? min(100, round(($alpaToday / $totalRecordedToday) * 100, 1)) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Tren & Status Rombel Hari Ini -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Tren 7 Hari Terakhir (Chart) -->
        <div class="lg:col-span-2 bg-white p-4 sm:p-5 rounded-xl border border-slate-200/90 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3.5">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Tren Kehadiran (7 Hari Terakhir)</h3>
                    <p class="text-xs text-slate-500">Distribusi presensi harian seluruh rombel</p>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <span class="flex items-center space-x-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span><span class="text-slate-600 text-[11px]">Hadir</span></span>
                    <span class="flex items-center space-x-1.5"><span class="w-2 h-2 rounded-full bg-amber-500"></span><span class="text-slate-600 text-[11px]">Terlambat</span></span>
                    <span class="flex items-center space-x-1.5"><span class="w-2 h-2 rounded-full bg-yellow-400"></span><span class="text-slate-600 text-[11px]">Sakit</span></span>
                    <span class="flex items-center space-x-1.5"><span class="w-2 h-2 rounded-full bg-sky-500"></span><span class="text-slate-600 text-[11px]">Izin</span></span>
                    <span class="flex items-center space-x-1.5"><span class="w-2 h-2 rounded-full bg-rose-500"></span><span class="text-slate-600 text-[11px]">Alpa</span></span>
                </div>
            </div>
            <div class="h-60 relative">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Monitoring Rombel (Status Absensi Hari Ini) -->
        <div class="bg-white p-4 sm:p-5 rounded-xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Monitoring Rombel</h3>
                        <p class="text-xs text-slate-500">Status input hari ini</p>
                    </div>
                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded {{ count($pendingClassesToday) > 0 ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                        {{ count($pendingClassesToday) }} Belum Diabsen
                    </span>
                </div>

                <div class="overflow-y-auto space-y-2 max-h-56 pr-1">
                    @forelse($pendingClassesToday as $cls)
                    <div class="p-2.5 rounded-lg border border-slate-200 bg-slate-50/60 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-slate-800">{{ $cls->name }}</p>
                            <p class="text-[10px] text-amber-600 font-medium">Belum diabsen hari ini</p>
                        </div>
                        <a href="{{ route('attendance.create', ['classroom_id' => $cls->id]) }}" class="text-[11px] font-medium px-2.5 py-1 rounded-md bg-slate-900 hover:bg-slate-800 text-white transition">
                            Input
                        </a>
                    </div>
                    @empty
                    <div class="p-5 text-center text-slate-500 my-auto">
                        <svg class="w-6 h-6 text-emerald-500 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="text-xs font-bold text-slate-800">Semua Kelas Selesai</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">Seluruh rombel telah memiliki catatan absensi hari ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Sesi Absensi Terakhir -->
    <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-200/80 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Sesi Absensi Terakhir</h3>
                <p class="text-xs text-slate-500">Riwayat pencatatan presensi yang baru saja disimpan</p>
            </div>
            <a href="{{ route('attendance.index') }}" class="text-xs font-medium text-slate-600 hover:text-slate-900 inline-flex items-center">
                <span>Lihat Semua Sesi</span>
                <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[10px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="py-2.5 px-4">Tanggal</th>
                        <th class="py-2.5 px-4">Rombel</th>
                        <th class="py-2.5 px-4">Sesi</th>
                        <th class="py-2.5 px-4">Kehadiran</th>
                        <th class="py-2.5 px-4">Pencatat</th>
                        <th class="py-2.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($recentSessions as $session)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3 px-4 text-slate-700 whitespace-nowrap text-xs font-medium">
                            {{ $session->attendance_date->isoFormat('D MMM Y') }}
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-900">
                            {{ $session->classroom->name }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $session->session_name }}
                            </span>
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap">
                            <span class="font-bold text-emerald-600">{{ $session->hadir_count }}</span>
                            <span class="text-slate-400">/ {{ $session->total_count }} Siswa</span>
                        </td>
                        <td class="py-3 px-4 text-slate-600 text-xs">
                            {{ $session->creator->name ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-right space-x-1 whitespace-nowrap">
                            <a href="{{ route('attendance.show', ['attendance' => $session->id]) }}" class="text-[11px] font-medium px-2.5 py-1 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 transition inline-block">
                                Detail
                            </a>
                            <a href="{{ route('attendance.edit', ['attendance' => $session->id]) }}" class="text-[11px] font-medium px-2.5 py-1 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 transition inline-block">
                                Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-slate-400 text-xs">
                            Belum ada riwayat sesi absensi yang tercatat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const trendData = @json($trendData);
        
        const labels = trendData.map(d => {
            if (!d.attendance_date) return '';
            const raw = String(d.attendance_date).split('T')[0].split(' ')[0];
            const parts = raw.split('-');
            if (parts.length === 3) {
                const year = parseInt(parts[0], 10);
                const month = parseInt(parts[1], 10) - 1;
                const day = parseInt(parts[2], 10);
                const date = new Date(year, month, day);
                return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            }
            const date = new Date(d.attendance_date);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        });

        const ctx = document.getElementById('trendChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Hadir',
                        data: trendData.map(d => d.hadir),
                        backgroundColor: '#10b981',
                        borderRadius: 3,
                    },
                    {
                        label: 'Terlambat',
                        data: trendData.map(d => d.terlambat || 0),
                        backgroundColor: '#f59e0b',
                        borderRadius: 3,
                    },
                    {
                        label: 'Sakit',
                        data: trendData.map(d => d.sakit),
                        backgroundColor: '#eab308',
                        borderRadius: 3,
                    },
                    {
                        label: 'Izin',
                        data: trendData.map(d => d.izin),
                        backgroundColor: '#0ea5e9',
                        borderRadius: 3,
                    },
                    {
                        label: 'Alpa',
                        data: trendData.map(d => d.alpa),
                        backgroundColor: '#f43f5e',
                        borderRadius: 3,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        stacked: true,
                        grid: { display: false }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endpush
