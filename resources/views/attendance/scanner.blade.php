@extends('layouts.app')

@section('title', 'Scan Presensi & Kedisiplinan Siswa')

@section('breadcrumbs')
    <a href="{{ route('dashboard') }}" class="hover:text-slate-800 transition">Dashboard</a>
    <span class="text-slate-400">/</span>
    <a href="{{ route('attendance.index') }}" class="hover:text-slate-800 transition">Presensi</a>
    <span class="text-slate-400">/</span>
    <span class="font-medium text-slate-800">Scanner & Pelacak Keterlambatan</span>
@endsection

@push('styles')
<style>
    #qr-reader {
        width: 100% !important;
        border: none !important;
    }
    #qr-reader video {
        border-radius: 0.75rem !important;
        object-fit: cover !important;
        width: 100% !important;
    }
    #qr-reader__scan_region {
        background: #0f172a !important;
        border-radius: 0.75rem !important;
    }
    @keyframes pulse-success {
        0%, 100% { background-color: transparent; }
        50% { background-color: #dcfce7; }
    }
    @keyframes pulse-late {
        0%, 100% { background-color: transparent; }
        50% { background-color: #fef3c7; }
    }
    .row-scanned {
        animation: pulse-success 1.5s ease-in-out;
    }
    .row-late {
        animation: pulse-late 1.5s ease-in-out;
    }
</style>
<!-- html5-qrcode CDN for in-browser QR scanning -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
@endpush

@section('content')
<div class="space-y-5">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                <span>Scan Presensi & Deteksi Keterlambatan Siswa</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Arahkan kartu smartcard siswa ke webcam atau gunakan tembakan scanner barcode fisik USB. Sistem otomatis menghitung menit telat, jam pelajaran terlewat, dan poin kedisiplinan.
            </p>
        </div>

        <div class="flex items-center space-x-2.5">
            @if(isset($session) && $session)
            <a href="{{ route('attendance.show', $session) }}" class="px-3.5 py-2 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-medium text-xs shadow-xs transition flex items-center space-x-2">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>Buka Detail Sesi</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Selector Kelas & Sesi Presensi -->
    <div class="bg-white p-4 sm:p-5 rounded-xl border border-slate-200/90 shadow-xs">
        <form method="GET" action="{{ route('attendance.scanner') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <!-- Pilihan Kelas -->
            <div class="sm:col-span-6">
                <label for="classroom_id" class="block text-xs font-semibold text-slate-700 mb-1">
                    Pilih Kelas / Rombel yang Sedang Presensi
                </label>
                <select id="classroom_id" name="classroom_id" onchange="this.form.submit()"
                    class="w-full px-3.5 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 bg-white transition">
                    <option value="">-- Pilih Kelas untuk Memulai Scan Presensi --</option>
                    @foreach($classrooms as $c)
                    <option value="{{ $c->id }}" {{ $selectedClassroom && $selectedClassroom->id == $c->id ? 'selected' : '' }}>
                        {{ $c->name }} ({{ $c->academicYear->year_name ?? '-' }}) — {{ $c->students_count }} Siswa
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Pilihan Sesi (Masuk / Pulang) -->
            <div class="sm:col-span-3">
                <label for="session_type" class="block text-xs font-semibold text-slate-700 mb-1">
                    Sesi Presensi
                </label>
                <select id="session_type" name="session_type" onchange="this.form.submit()"
                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 bg-white transition">
                    <option value="masuk" {{ ($sessionType ?? 'masuk') === 'masuk' ? 'selected' : '' }}>🌅 Sesi Masuk (Pagi)</option>
                    <option value="pulang" {{ ($sessionType ?? 'masuk') === 'pulang' ? 'selected' : '' }}>🌆 Sesi Pulang (Sore)</option>
                </select>
            </div>

            <!-- Info Batas Toleransi Jam Masuk Sekolah -->
            <div class="sm:col-span-3">
                <div class="p-2 rounded-lg bg-slate-50 border border-slate-200 text-center">
                    <span class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Jadwal Bel Masuk</span>
                    <span class="block text-xs font-bold text-slate-900">
                        {{ $schoolSetting->entry_time ?? '07:15' }} WIB
                        @if(($schoolSetting->tolerance_minutes ?? 0) > 0)
                        <span class="text-[10px] text-slate-500 font-normal">(+{{ $schoolSetting->tolerance_minutes }}m)</span>
                        @endif
                    </span>
                </div>
            </div>
        </form>
    </div>

    @if(!$selectedClassroom)
    <!-- Empty State -->
    <div class="bg-white rounded-xl border border-slate-200/90 p-10 text-center max-w-lg mx-auto shadow-xs">
        <div class="w-12 h-12 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
            </svg>
        </div>
        <h3 class="text-sm font-bold text-slate-900">Pilih Rombongan Belajar</h3>
        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
            Pilih kelas pada formulir di atas untuk membuka sesi presensi hari ini. Kamera scanner dan USB barcode reader akan langsung aktif.
        </p>
    </div>
    @else

    <!-- Ringkasan Statistik Presensi Hari Ini (Termasuk Terlambat T) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <!-- Hadir Tepat Waktu -->
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Tepat Waktu</p>
                <p id="statHadir" class="text-xl font-bold text-emerald-600 mt-0.5">{{ $stats['hadir'] }}</p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-xs border border-emerald-100">
                H
            </div>
        </div>

        <!-- Terlambat -->
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Terlambat</p>
                <p id="statTerlambat" class="text-xl font-bold text-amber-600 mt-0.5">{{ $stats['terlambat'] }}</p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-xs border border-amber-100">
                T
            </div>
        </div>

        <!-- Sakit -->
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Sakit</p>
                <p id="statSakit" class="text-xl font-bold text-amber-500 mt-0.5">{{ $stats['sakit'] }}</p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs border border-amber-100">
                S
            </div>
        </div>

        <!-- Izin -->
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Izin</p>
                <p id="statIzin" class="text-xl font-bold text-sky-600 mt-0.5">{{ $stats['izin'] }}</p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-700 flex items-center justify-center font-bold text-xs border border-sky-100">
                I
            </div>
        </div>

        <!-- Alpa -->
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Alpa</p>
                <p id="statAlpa" class="text-xl font-bold text-rose-600 mt-0.5">{{ $stats['alpa'] }}</p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-700 flex items-center justify-center font-bold text-xs border border-rose-100">
                A
            </div>
        </div>

        <!-- % Kehadiran Total -->
        <div class="bg-slate-900 text-white p-3.5 rounded-xl border border-slate-800 shadow-xs flex items-center justify-between col-span-2 sm:col-span-1">
            <div>
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">% Hadir</p>
                <p id="statPercentage" class="text-xl font-bold text-white mt-0.5">{{ $stats['percentage'] }}%</p>
            </div>
            <div class="text-[10px] text-right text-slate-300 font-medium">
                <span id="statSummary">{{ $stats['hadir'] + $stats['terlambat'] }}/{{ $stats['total'] }}</span><br>Siswa
            </div>
        </div>
    </div>

    <!-- Workspace 2 Kolom: Kiri Scanner & Kanan Live Roster -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
        
        <!-- Kolom Kiri: Scanner Kamera & Barcode Input (5 Kolom) -->
        <div class="lg:col-span-5 space-y-4">
            <!-- Card Kamera Scanner -->
            <div class="bg-white p-4 sm:p-5 rounded-xl border border-slate-200/90 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold text-xs text-slate-900 uppercase tracking-wide flex items-center space-x-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Viewport Kamera Scanner</span>
                    </h3>
                    <button type="button" id="btnToggleCamera" onclick="toggleCamera()" 
                        class="px-2.5 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-medium text-xs transition">
                        Nyalakan Kamera
                    </button>
                </div>

                <!-- Wadah Video QR -->
                <div id="scannerWrapper" class="relative rounded-lg overflow-hidden bg-slate-950 aspect-square flex flex-col items-center justify-center text-white border border-slate-800">
                    <div id="qr-reader" class="w-full h-full"></div>
                    <div id="cameraPlaceholder" class="p-6 text-center space-y-2">
                        <svg class="w-10 h-10 text-slate-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <p class="text-xs text-slate-400">Kamera scanner nonaktif.<br>Klik tombol di atas untuk mengaktifkan webcam.</p>
                    </div>
                </div>

                <!-- Input Cepat Barcode / Scanner Tembak USB -->
                <div class="mt-4 pt-3.5 border-t border-slate-100">
                    <label for="manualNisInput" class="block text-xs font-semibold text-slate-700 mb-1.5 flex items-center justify-between">
                        <span>Barcode Scanner USB / Input NIS</span>
                        <span class="text-[10px] text-slate-500 font-medium">Auto-Focus Aktif</span>
                    </label>
                    <form id="formManualScan" onsubmit="handleManualScan(event)" class="relative">
                        <input type="text" id="manualNisInput" autocomplete="off" placeholder="Tembak barcode atau ketik NIS..."
                            class="w-full pl-3 pr-20 py-2 rounded-lg border border-slate-300 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                        <button type="submit" class="absolute right-1 top-1 bottom-1 px-3 rounded-md bg-slate-900 hover:bg-slate-800 text-white font-medium text-xs transition">
                            Proses
                        </button>
                    </form>
                </div>
            </div>

            <!-- Kartu Siswa Terakhir Terscan (Lengkap dengan Deteksi Keterlambatan & Sanksi) -->
            <div id="lastScannedCard" class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-xs hidden space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-500">Hasil Pemindaian Kartu</span>
                    <span id="scanTimeBadge" class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                        00:00:00
                    </span>
                </div>

                <!-- Baris Biodata Siswa -->
                <div class="flex items-center space-x-3">
                    <div id="scannedAvatar" class="w-10 h-10 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
                        AP
                    </div>
                    <div class="overflow-hidden flex-1">
                        <h4 id="scannedName" class="text-xs font-bold text-slate-900 truncate">Nama Siswa</h4>
                        <div class="flex items-center space-x-2 text-[11px] text-slate-500 mt-0.5">
                            <span id="scannedNis" class="font-semibold text-slate-700">NIS: -</span>
                            <span>&bull;</span>
                            <span id="scannedClass" class="text-slate-700 font-semibold">Kelas: -</span>
                        </div>
                    </div>
                    <span id="scannedStatusBadge" class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 shrink-0">
                        HADIR
                    </span>
                </div>

                <!-- Panel Khusus Keterlambatan, Jam Pelajaran Terlewat & Sanksi Poin -->
                <div id="lateDetailsPanel" class="hidden p-3 rounded-lg bg-amber-50/80 border border-amber-200 space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-amber-900 flex items-center space-x-1">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span id="lateMinutesText">Terlambat +0 Menit</span>
                        </span>
                        <span id="latePointsBadge" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-200 text-amber-900">
                            +0 Poin Disiplin
                        </span>
                    </div>

                    <div class="text-[11px] text-amber-800">
                        <strong>Dampak Timeline:</strong> <span id="missedPeriodsText">-</span>
                    </div>

                    <div class="text-[11px] text-amber-900 border-t border-amber-200/60 pt-1.5 flex items-start space-x-1.5">
                        <span class="font-bold">Konsekuensi:</span>
                        <span id="compensationTaskText">-</span>
                    </div>

                    <!-- Peringatan Akumulasi Telat / Surat Panggilan Orang Tua -->
                    <div id="parentCallAlert" class="hidden p-2 rounded bg-rose-100 border border-rose-300 text-rose-800 font-bold text-[10px] flex items-center space-x-1.5 animate-pulse">
                        <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>PERINGATAN: Siswa sudah telat ≥ 3x bulan ini. Hubungi Orang Tua / Guru BK!</span>
                    </div>

                    <!-- Tombol 1-Klik Hubungi Orang Tua via WhatsApp -->
                    <div id="waParentAction" class="hidden pt-1">
                        <a id="btnWhatsappParent" href="#" target="_blank" rel="noopener noreferrer"
                            class="w-full flex items-center justify-center space-x-1.5 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.031 2C6.49 2 2 6.48 2 12.016c0 1.95.556 3.824 1.606 5.438L2.016 22l4.693-1.554a9.96 9.96 0 005.322 1.556h.005C17.564 22 22.05 17.52 22.05 11.984 22.05 6.448 17.564 2 12.031 2zm5.787 14.288c-.244.686-1.42 1.32-1.95 1.39-.508.068-1.157.102-3.72-1.002-3.11-1.33-5.09-4.526-5.244-4.736-.154-.21-1.258-1.674-1.258-3.193 0-1.518.796-2.267 1.08-2.57.284-.303.62-.378.827-.378.207 0 .414.002.593.01.19.01.442-.072.693.53.257.618.88 2.146.957 2.302.078.156.13.338.026.544-.103.207-.156.336-.31.517-.155.18-.328.403-.467.54-.156.155-.318.324-.136.637.181.313.805 1.327 1.726 2.147 1.185 1.054 2.183 1.381 2.494 1.536.31.155.492.13.673-.077.182-.207.777-.905.984-1.215.207-.31.414-.259.697-.155.284.103 1.802.85 2.112 1.005.31.155.518.232.594.362.077.13.077.75-.167 1.436z"/>
                            </svg>
                            <span>Kirim Laporan WA ke Orang Tua</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Feedback Log Notifikasi -->
            <div id="scanAlert" class="hidden p-3 rounded-lg text-xs font-medium flex items-center space-x-2 shadow-xs"></div>
        </div>

        <!-- Kolom Kanan: Live Roster & Status Presensi Hari Ini (7 Kolom) -->
        <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200/90 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="font-bold text-xs uppercase tracking-wide text-slate-900">
                        Roster Presensi Kelas: {{ $selectedClassroom->name }}
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">
                        Wali Kelas: <strong class="text-slate-700">{{ $selectedClassroom->waliKelas->name ?? 'Belum Ditentukan' }}</strong> &bull; Sesi: <strong class="text-slate-800">{{ $sessionType === 'pulang' ? 'Sore (Pulang)' : 'Pagi (Masuk)' }}</strong>
                    </p>
                </div>
                <!-- Pencarian Cepat di Roster -->
                <div class="w-full sm:w-44">
                    <input type="text" id="rosterSearch" oninput="filterRoster()" placeholder="Cari nama/NIS..."
                        class="w-full px-2.5 py-1.5 rounded-lg border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 bg-white transition">
                </div>
            </div>

            <div class="overflow-x-auto max-h-[580px] overflow-y-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="sticky top-0 bg-slate-100 border-b border-slate-200 text-[10px] font-semibold uppercase tracking-wider text-slate-500 z-10">
                        <tr>
                            <th class="py-2.5 px-3.5 w-10 text-center">No</th>
                            <th class="py-2.5 px-3.5 w-24">NIS</th>
                            <th class="py-2.5 px-3.5">Nama Siswa</th>
                            <th class="py-2.5 px-3.5 w-24 text-center">Status</th>
                            <th class="py-2.5 px-3.5 text-right w-20">Waktu</th>
                        </tr>
                    </thead>
                    <tbody id="rosterBody" class="divide-y divide-slate-100 font-medium">
                        @forelse($students as $st)
                        @php $rec = $attendances->get($st->id); @endphp
                        <tr id="row-student-{{ $st->nis }}" class="hover:bg-slate-50/80 transition {{ $rec && $rec->status == 'H' ? 'bg-emerald-50/30' : ($rec && $rec->status == 'T' ? 'bg-amber-50/40' : '') }}">
                            <td class="py-2.5 px-3.5 text-center text-slate-400 text-xs">{{ $loop->iteration }}</td>
                            <td class="py-2.5 px-3.5 text-slate-700 font-semibold student-nis text-xs">{{ $st->nis }}</td>
                            <td class="py-2.5 px-3.5 font-semibold text-slate-900 student-name">
                                {{ $st->name }}
                                <span class="text-[10px] font-normal text-slate-400 block">{{ $st->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            </td>
                            <td class="py-2.5 px-3.5 text-center" id="status-cell-{{ $st->nis }}">
                                @if($rec && $rec->status == 'H')
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Hadir
                                    </span>
                                @elseif($rec && $rec->status == 'T')
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-800 border border-amber-200" title="{{ $rec->notes }}">
                                        Telat {{ $rec->late_minutes }}m
                                    </span>
                                @elseif($rec && $rec->status == 'S')
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        Sakit
                                    </span>
                                @elseif($rec && $rec->status == 'I')
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-sky-100 text-sky-800 border border-sky-200">
                                        Izin
                                    </span>
                                @else
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-400 border border-slate-200">
                                        Belum
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3.5 text-right text-xs text-slate-500 font-medium" id="time-cell-{{ $st->nis }}">
                                {{ $rec ? $rec->updated_at->format('H:i') : '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-10 px-4 text-center">
                                <div class="max-w-sm mx-auto space-y-2">
                                    <h4 class="font-bold text-xs text-slate-800">Belum Ada Siswa di Kelas Ini</h4>
                                    <p class="text-xs text-slate-500 leading-relaxed">
                                        Kelas <strong class="text-slate-700">{{ $selectedClassroom->name }}</strong> belum memiliki data siswa terdaftar.
                                    </p>
                                    <div class="pt-2 flex items-center justify-center gap-2">
                                        <a href="{{ route('students.create') }}" class="px-3 py-1.5 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-medium text-xs transition">
                                            + Tambah Siswa
                                        </a>
                                        <a href="{{ route('students.batch-create') }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs border border-slate-200 transition">
                                            Input Massal
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
@if($selectedClassroom)
<script>
    const classroomId = {{ $selectedClassroom->id }};
    const sessionType = "{{ $sessionType ?? 'masuk' }}";
    let html5QrCode = null;
    let cameraRunning = false;
    let isProcessing = false;

    // Web Audio Synthesizer untuk Audio Beep
    function playBeep(type = 'success') {
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();

            osc.connect(gain);
            gain.connect(audioCtx.destination);

            if (type === 'success') {
                // High crisp single beep
                osc.type = 'sine';
                osc.frequency.setValueAtTime(880, audioCtx.currentTime); // A5
                gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.12);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.12);
            } else if (type === 'late') {
                // Two-tone warning alert (A4 -> E4)
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(440, audioCtx.currentTime);
                osc.frequency.setValueAtTime(330, audioCtx.currentTime + 0.1);
                gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.28);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.28);
            } else {
                // Low buzz error
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(220, audioCtx.currentTime);
                gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.2);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.2);
            }
        } catch (e) {
            console.log("Audio not supported or allowed", e);
        }
    }

    // Toggle Camera
    function toggleCamera() {
        if (cameraRunning) {
            stopCamera();
        } else {
            startCamera();
        }
    }

    function startCamera() {
        const qrWrapper = document.getElementById('cameraPlaceholder');
        if (cameraRunning) return;

        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("qr-reader", { experimentalFeatures: { useBarCodeDetectorIfSupported: true } });
        }

        const config = { 
            fps: 15, 
            qrbox: (viewfinderWidth, viewfinderHeight) => {
                const minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                const qrboxSize = Math.max(180, Math.floor(minEdge * 0.75));
                return { width: qrboxSize, height: qrboxSize };
            } 
        };

        const onScanSuccess = (decodedText) => {
            if (!isProcessing) {
                processScan(decodedText);
            }
        };

        // Query camera devices first (works flawlessly on Windows laptops and mobile browsers)
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                let selectedCamId = devices[0].id;
                for (let d of devices) {
                    const lbl = (d.label || '').toLowerCase();
                    if (lbl.includes('back') || lbl.includes('rear') || lbl.includes('environment')) {
                        selectedCamId = d.id;
                        break;
                    }
                }
                html5QrCode.start(selectedCamId, config, onScanSuccess, () => {})
                    .then(() => {
                        qrWrapper.classList.add('hidden');
                        onCameraStarted();
                    })
                    .catch(() => {
                        html5QrCode.start({ facingMode: "user" }, config, onScanSuccess, () => {})
                            .then(() => {
                                qrWrapper.classList.add('hidden');
                                onCameraStarted();
                            })
                            .catch(err2 => {
                                console.warn('Kamera error:', err2);
                                qrWrapper.classList.remove('hidden');
                            });
                    });
            } else {
                html5QrCode.start({ facingMode: "user" }, config, onScanSuccess, () => {})
                    .then(() => {
                        qrWrapper.classList.add('hidden');
                        onCameraStarted();
                    })
                    .catch(err => {
                        console.warn('Kamera error:', err);
                        qrWrapper.classList.remove('hidden');
                    });
            }
        }).catch(() => {
            html5QrCode.start({ facingMode: "user" }, config, onScanSuccess, () => {})
                .then(() => {
                    qrWrapper.classList.add('hidden');
                    onCameraStarted();
                })
                .catch(err2 => {
                    console.warn('Kamera error:', err2);
                    qrWrapper.classList.remove('hidden');
                });
        });
    }

    function onCameraStarted() {
        cameraRunning = true;
        const btn = document.getElementById('btnToggleCamera');
        btn.textContent = 'Matikan Kamera';
        btn.classList.replace('bg-slate-900', 'bg-rose-600');
        btn.classList.replace('hover:bg-slate-800', 'hover:bg-rose-700');
    }

    function stopCamera() {
        if (html5QrCode && cameraRunning) {
            html5QrCode.stop().then(() => {
                cameraRunning = false;
                const btn = document.getElementById('btnToggleCamera');
                btn.textContent = 'Nyalakan Kamera';
                btn.classList.replace('bg-rose-600', 'bg-slate-900');
                btn.classList.replace('hover:bg-rose-700', 'hover:bg-slate-800');
                document.getElementById('cameraPlaceholder').classList.remove('hidden');
            });
        }
    }

    // Handle Manual / USB Scanner Input
    function handleManualScan(e) {
        e.preventDefault();
        const input = document.getElementById('manualNisInput');
        const code = input.value.trim();
        if (!code) return;

        input.value = '';
        processScan(code);
    }

    // Process Scan Request to Server
    function processScan(code) {
        isProcessing = true;

        fetch("{{ route('attendance.scanner.scan') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                code: code,
                classroom_id: classroomId,
                session_type: sessionType
            })
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Terjadi kesalahan saat memproses scan.');
            }
            return data;
        })
        .then(data => {
            if (data.student.is_late) {
                playBeep('late');
            } else {
                playBeep('success');
            }

            showLastScanned(data.student, data.time);
            updateRosterRow(data.student, data.time);
            updateStats(data.stats);
            showAlert(data.student.is_late ? 'warning' : 'success', data.message);
        })
        .catch(err => {
            playBeep('error');
            showAlert('error', err.message);
        })
        .finally(() => {
            setTimeout(() => {
                isProcessing = false;
                const input = document.getElementById('manualNisInput');
                if (input) input.focus({ preventScroll: true });
            }, 1200);
        });
    }

    // Tampilkan data siswa di kartu preview lengkap dengan info keterlambatan & WA ortu
    function showLastScanned(student, time) {
        const card = document.getElementById('lastScannedCard');
        card.classList.remove('hidden');
        document.getElementById('scannedName').textContent = student.name;
        document.getElementById('scannedNis').textContent = 'NIS: ' + student.nis;
        document.getElementById('scannedClass').textContent = 'Kelas: ' + student.classroom;
        document.getElementById('scanTimeBadge').textContent = time;

        const avatarEl = document.getElementById('scannedAvatar');
        if (student.photo_url) {
            avatarEl.innerHTML = `<img src="${student.photo_url}" class="w-full h-full object-cover object-top rounded-lg">`;
        } else {
            const initials = student.name.split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase();
            avatarEl.textContent = initials;
        }

        const statusBadge = document.getElementById('scannedStatusBadge');
        const latePanel = document.getElementById('lateDetailsPanel');

        if (student.is_late) {
            statusBadge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-300 shrink-0';
            statusBadge.textContent = 'TERLAMBAT';

            latePanel.classList.remove('hidden');
            document.getElementById('lateMinutesText').textContent = `Terlambat +${student.late_minutes} Menit`;
            document.getElementById('latePointsBadge').textContent = `+${student.discipline_points} Poin Disiplin`;
            document.getElementById('missedPeriodsText').textContent = student.missed_periods || '-';
            document.getElementById('compensationTaskText').textContent = student.compensation_task || '-';

            // Panggilan Orang Tua Alert
            const spAlert = document.getElementById('parentCallAlert');
            if (student.needs_parent_call) {
                spAlert.classList.remove('hidden');
            } else {
                spAlert.classList.add('hidden');
            }

            // WhatsApp Button
            const waAction = document.getElementById('waParentAction');
            const btnWa = document.getElementById('btnWhatsappParent');
            if (student.parent_whatsapp_url) {
                waAction.classList.remove('hidden');
                btnWa.href = student.parent_whatsapp_url;
            } else {
                waAction.classList.add('hidden');
            }
        } else {
            statusBadge.className = 'px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 shrink-0';
            statusBadge.textContent = 'HADIR';
            latePanel.classList.add('hidden');
        }
    }

    // Perbarui baris siswa di tabel roster
    function updateRosterRow(student, time) {
        const row = document.getElementById('row-student-' + student.nis);
        if (row) {
            row.classList.remove('row-scanned', 'row-late');
            void row.offsetWidth; // trigger reflow

            if (student.is_late) {
                row.classList.add('row-late', 'bg-amber-50/50');
            } else {
                row.classList.add('row-scanned', 'bg-emerald-50/50');
            }

            const statusCell = document.getElementById('status-cell-' + student.nis);
            if (statusCell) {
                if (student.is_late) {
                    statusCell.innerHTML = `<span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-800 border border-amber-200" title="Terlambat ${student.late_minutes} menit">Telat ${student.late_minutes}m</span>`;
                } else {
                    statusCell.innerHTML = `<span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">Hadir</span>`;
                }
            }

            const timeCell = document.getElementById('time-cell-' + student.nis);
            if (timeCell) {
                timeCell.textContent = time.substring(0, 5);
            }
        }
    }

    // Perbarui angka ringkasan statistik
    function updateStats(stats) {
        if (!stats) return;
        document.getElementById('statHadir').textContent = stats.hadir;
        document.getElementById('statTerlambat').textContent = stats.terlambat;
        document.getElementById('statSakit').textContent = stats.sakit;
        document.getElementById('statIzin').textContent = stats.izin;
        document.getElementById('statAlpa').textContent = stats.alpa;
        document.getElementById('statPercentage').textContent = stats.percentage + '%';
        document.getElementById('statSummary').textContent = `${stats.hadir + stats.terlambat}/${stats.total}`;
    }

    function showAlert(type, message) {
        const alertBox = document.getElementById('scanAlert');
        alertBox.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200', 'bg-amber-50', 'text-amber-900', 'border-amber-200', 'bg-rose-50', 'text-rose-800', 'border-rose-200');

        if (type === 'success') {
            alertBox.classList.add('bg-emerald-50', 'text-emerald-800', 'border', 'border-emerald-200');
            alertBox.innerHTML = `
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>${message}</span>
            `;
        } else if (type === 'warning') {
            alertBox.classList.add('bg-amber-50', 'text-amber-900', 'border', 'border-amber-200');
            alertBox.innerHTML = `
                <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>${message}</span>
            `;
        } else {
            alertBox.classList.add('bg-rose-50', 'text-rose-800', 'border', 'border-rose-200');
            alertBox.innerHTML = `
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span>${message}</span>
            `;
        }
    }

    function filterRoster() {
        const query = document.getElementById('rosterSearch').value.toLowerCase();
        const rows = document.querySelectorAll('#rosterBody tr');
        rows.forEach(r => {
            const nameEl = r.querySelector('.student-name');
            const nisEl = r.querySelector('.student-nis');
            if (!nameEl || !nisEl) return;

            const name = nameEl.textContent.toLowerCase();
            const nis = nisEl.textContent.toLowerCase();
            if (name.includes(query) || nis.includes(query)) {
                r.style.display = '';
            } else {
                r.style.display = 'none';
            }
        });
    }

    // Auto-focus barcode input kembali setiap kali klik di luar input tanpa scroll
    document.addEventListener('click', function(e) {
        if (!['INPUT', 'SELECT', 'BUTTON', 'A', 'TEXTAREA'].includes(e.target.tagName)) {
            const input = document.getElementById('manualNisInput');
            if (input) input.focus({ preventScroll: true });
        }
    });

    // Inisialisasi awal saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        window.scrollTo(0, 0);
        const input = document.getElementById('manualNisInput');
        if (input) {
            input.focus({ preventScroll: true });
        }
        // Auto-start camera on classroom selected
        setTimeout(() => {
            startCamera();
        }, 300);
    });
</script>
@endif
@endpush
@endsection