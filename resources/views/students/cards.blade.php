@extends('layouts.app')

@section('title', 'Cetak Kartu Tanda Pelajar — SMK Darus Sa\'adah')

@section('breadcrumbs')
    <a href="{{ route('dashboard') }}" class="hover:text-slate-800 transition">Dashboard</a>
    <span class="text-slate-400">/</span>
    <a href="{{ route('students.index') }}" class="hover:text-slate-800 transition">Data Siswa</a>
    <span class="text-slate-400">/</span>
    <span class="font-semibold text-slate-800">Cetak Kartu Pelajar</span>
@endsection

@push('styles')
<style>
    /* Ukuran kartu standar ID-1 ISO 7810: 85.6mm x 54mm */
    .id-card-wrapper {
        width: 100%;
        max-width: 400px;
        aspect-ratio: 85.6 / 54;
        min-height: 240px;
        position: relative;
        background-color: #fcfdfd;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #cbd5e1;
    }
    .card-security-pattern {
        background-color: #fcfdfd;
        background-image: 
            radial-gradient(circle at 100% 0%, rgba(5, 150, 105, 0.04) 0%, transparent 60%),
            radial-gradient(circle at 0% 100%, rgba(217, 119, 6, 0.03) 0%, transparent 50%),
            linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
    }

    /* Print Calibration untuk Kertas A4 (Tepat 8 Kartu per Lembar, Presisi Tanpa Terpotong) */
    @media print {
        @page {
            size: A4 portrait;
            margin: 8mm 10mm;
        }
        body, html {
            background: #ffffff !important;
            padding: 0 !important;
            margin: 0 !important;
            height: auto !important;
            overflow: visible !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .no-print, header, nav, aside, #mainSidebar, .no-print * {
            display: none !important;
        }
        main {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
            overflow: visible !important;
        }
        .cards-print-container {
            display: grid !important;
            grid-template-columns: repeat(2, 85.6mm) !important;
            justify-content: center !important;
            gap: 5mm !important;
            width: 100% !important;
            margin: 0 auto !important;
        }
        .id-card-wrapper {
            width: 85.6mm !important;
            height: 54mm !important;
            max-width: 85.6mm !important;
            max-height: 54mm !important;
            min-height: 54mm !important;
            break-inside: avoid !important;
            page-break-inside: avoid !important;
            box-shadow: none !important;
            border: 1px solid #94a3b8 !important;
            border-radius: 6px !important;
            overflow: hidden !important;
            box-sizing: border-box !important;
            margin: 0 !important;
        }
    }
</style>
<!-- QRCode.js library for high-resolution offline QR rendering -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endpush

@section('content')
@php 
    $schoolSetting = \App\Models\SchoolSetting::get();
    $schoolAddress = $schoolSetting->school_address ?? 'Jl. Alternatif Sentul No. 38, RT. 06/RW. 07, Desa Cijujung, Kec. Sukaraja, Kab. Bogor, Jawa Barat 16710';
@endphp

<div class="space-y-5">
    <!-- Toolbar Kontrol Cetak & Filter -->
    <div class="no-print bg-white p-4 sm:p-5 rounded-xl border border-slate-200/90 shadow-xs space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2">
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Cetak Kartu Tanda Pelajar</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        Format Resmi SMK
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Kartu tanda pelajar resmi <strong>SMK Darus Sa'adah</strong> dengan QR code presensi terintegrasi untuk scanner webcam dan barcode reader.
                </p>
            </div>

            <!-- Tombol Tambah Siswa & Cetak -->
            <div class="flex flex-wrap items-center gap-2">
                @if(auth()->user()?->isAdmin())
                <a href="{{ route('students.create') }}" 
                    class="px-3 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs shadow-xs transition flex items-center space-x-1.5">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Siswa Baru</span>
                </a>
                <a href="{{ route('students.batch-create') }}" 
                    class="px-3 py-2 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-xs shadow-xs transition flex items-center space-x-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span>Input Massal</span>
                </a>
                @endif
                <button type="button" onclick="window.print()" 
                    class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition flex items-center space-x-2">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Kartu (PDF/A4)</span>
                </button>
            </div>
        </div>

        <!-- Filter Kelas & Switcher Sisi Kartu -->
        <div class="pt-3 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs">
            <div class="flex flex-wrap items-center gap-2.5">
                <!-- Filter Kelas -->
                <form method="GET" action="{{ route('students.cards') }}" class="flex items-center space-x-2">
                    <select name="classroom_id" onchange="this.form.submit()" 
                        class="px-3 py-1.5 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 bg-white hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 transition">
                        <option value="">Semua Rombel ({{ $students->count() }} Siswa)</option>
                        @foreach($classrooms as $c)
                        <option value="{{ $c->id }}" {{ $selectedClassroomId == $c->id ? 'selected' : '' }}>
                            {{ $c->name }} ({{ $c->students_count }} Siswa)
                        </option>
                        @endforeach
                    </select>
                </form>

                <!-- Switcher Sisi Kartu -->
                <div class="flex items-center bg-slate-100 p-0.5 rounded-lg border border-slate-200">
                    <button type="button" onclick="switchCardSide('front')" id="btnSideFront"
                        class="px-3 py-1 rounded-md text-xs font-semibold bg-white text-emerald-800 shadow-2xs transition">
                        Sisi Depan
                    </button>
                    <button type="button" onclick="switchCardSide('back')" id="btnSideBack"
                        class="px-3 py-1 rounded-md text-xs font-medium text-slate-600 hover:text-slate-800 transition">
                        Sisi Belakang
                    </button>
                    <button type="button" onclick="switchCardSide('both')" id="btnSideBoth"
                        class="px-3 py-1 rounded-md text-xs font-medium text-slate-600 hover:text-slate-800 transition">
                        Depan & Belakang
                    </button>
                </div>
            </div>

            <!-- Instant Search Input -->
            <div class="w-full sm:w-56">
                <input type="text" id="cardSearchInput" oninput="filterCards()" placeholder="Cari nama atau NIS siswa..."
                    class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 bg-white transition">
            </div>
        </div>
    </div>

    <!-- Alert Petunjuk Cetak -->
    <div class="no-print p-3.5 rounded-xl bg-emerald-50/60 border border-emerald-200 text-emerald-900 text-xs flex items-center justify-between">
        <div class="flex items-center space-x-2.5">
            <svg class="w-4 h-4 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span><strong>Panduan Cetak A4:</strong> Satu lembar kertas A4 memuat tepat 8 kartu tanda pelajar (2 kolom x 4 baris). Pada dialog print browser, pilih ukuran <strong>A4</strong>, margin <strong>Default</strong>, dan centang opsi <strong>Background graphics</strong>.</span>
        </div>
    </div>

    <!-- Grid Kartu Pelajar Siap Cetak -->
    <div id="cardsGridContainer" class="cards-print-container grid grid-cols-1 md:grid-cols-2 gap-5 max-w-4xl mx-auto">
        @forelse($students as $st)
        
        <!-- WRAPPER KARTU SISWA: SISI DEPAN -->
        <div id="card-front-{{ $st->id }}" class="card-item-wrapper card-front-item" data-name="{{ strtolower($st->name) }}" data-nis="{{ $st->nis }}">
            <div class="id-card-wrapper border border-slate-300 flex flex-col justify-between card-security-pattern mx-auto bg-white">
                
                <!-- Header Resmi Kartu Pelajar SMK Darus Sa'adah -->
                <div class="bg-gradient-to-r from-emerald-950 via-emerald-800 to-emerald-900 text-white px-2.5 py-1.5 flex items-center justify-between border-b-2 border-amber-400 relative overflow-hidden">
                    <!-- Subtle background glow accent -->
                    <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-amber-400/10 pointer-events-none"></div>

                    <div class="flex items-center space-x-2 overflow-hidden flex-1 relative z-10">
                        <!-- Logo Resmi SMK Darus Sa'adah -->
                        <div class="w-8 h-8 rounded-full bg-white p-0.5 shrink-0 shadow-xs flex items-center justify-center border border-amber-300">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo SMK Darus Sa'adah" class="w-full h-full object-contain">
                        </div>
                        <div class="overflow-visible leading-tight flex-1">
                            <span class="block text-[6.5px] font-bold text-amber-300 uppercase tracking-widest leading-normal">
                                YAYASAN PENDIDIKAN DARUS SA'ADAH
                            </span>
                            <h4 class="text-[11.5px] font-extrabold text-white uppercase tracking-tight mt-0.5 leading-snug">
                                {{ $schoolSetting->school_name ?? 'SMK DARUS SA\'ADAH' }}
                            </h4>
                            <p class="text-[7.2px] text-emerald-50 font-medium leading-normal mt-0.5 tracking-tight">
                                Jl. Alternatif Sentul No. 38, RT. 06/07, Cijujung, Sukaraja, Bogor
                            </p>
                        </div>
                    </div>
                    
                    <!-- Badge Kartu Pelajar Resmi -->
                    <div class="text-right shrink-0 ml-1.5 relative z-10">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-400 text-slate-950 text-[6.8px] font-extrabold uppercase tracking-tight shadow-xs border border-amber-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-950"></span>
                            KARTU PELAJAR
                        </span>
                    </div>
                </div>

                <!-- Konten Tengah: Pas Foto + Biodata Siswa + QR Presensi -->
                <div class="px-2.5 py-1.5 flex items-center gap-2 flex-1">
                    
                    <!-- Kolom Kiri: Pas Foto Formal 3x4 -->
                    <div class="w-[19mm] shrink-0 flex flex-col items-center justify-center">
                        <div class="w-[19mm] h-[25mm] rounded-md border-2 border-slate-200 bg-white shadow-xs p-0.5 flex flex-col justify-between overflow-hidden">
                            @if($st->photo && file_exists(public_path($st->photo)))
                                <div class="w-full h-full rounded overflow-hidden relative">
                                    <img src="{{ asset($st->photo) }}" alt="{{ $st->name }}" class="w-full h-full object-cover object-top">
                                    <div class="absolute bottom-0 inset-x-0 bg-slate-950/75 py-0.5 text-center">
                                        <span class="block text-[6px] font-bold text-amber-300 uppercase leading-none">{{ $st->gender == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="w-full h-full rounded bg-gradient-to-b from-sky-900 via-indigo-950 to-slate-900 flex flex-col items-center justify-between p-1 overflow-hidden text-white">
                                    <div class="w-6 h-6 rounded-full bg-white/20 border border-white/30 flex items-center justify-center font-bold text-[10px] mt-1 text-white">
                                        {{ strtoupper(substr($st->name, 0, 2)) }}
                                    </div>
                                    <div class="w-full text-center pb-0.5">
                                        <span class="block text-[6px] font-semibold text-sky-200 tracking-wider uppercase leading-none">FOTO 3X4</span>
                                        <span class="block text-[6.8px] font-bold text-amber-300 uppercase leading-none mt-0.5">{{ $st->gender == 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Kolom Tengah: Biodata Siswa Terstruktur -->
                    <div class="flex-1 min-w-0 flex flex-col justify-center">
                        <div class="border-b border-slate-200 pb-0.5 mb-1">
                            <span class="block text-[6px] font-bold text-emerald-800 tracking-wider uppercase leading-none">BIODATA SISWA</span>
                            <h3 class="text-[11.5px] font-extrabold text-slate-900 uppercase tracking-tight truncate leading-tight mt-0.5">
                                {{ $st->name }}
                            </h3>
                        </div>

                        <table class="w-full text-[8.2px] text-slate-700 leading-tight">
                            <tbody>
                                <tr>
                                    <td class="text-slate-400 font-semibold py-0.5 w-11">NIS</td>
                                    <td class="py-0.5 font-extrabold text-slate-900">: {{ $st->nis }}</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-400 font-semibold py-0.5">NISN</td>
                                    <td class="py-0.5 font-medium text-slate-800">: {{ $st->nisn ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-400 font-semibold py-0.5">KELAS</td>
                                    <td class="py-0.5 font-bold text-emerald-900">
                                        : <span class="inline-block px-1.5 py-0.2 rounded bg-emerald-700 text-white font-extrabold text-[7.5px] leading-tight">{{ $st->classroom->name ?? '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-slate-400 font-semibold py-0.5">T.A.</td>
                                    <td class="py-0.5 text-slate-700 font-medium">: {{ $st->classroom->academicYear->year_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-slate-400 font-semibold py-0.5">BERLAKU</td>
                                    <td class="py-0.5 text-slate-600 font-medium">: SELAMA MENJADI SISWA</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Kolom Kanan: QR Code Presensi Resmi -->
                    <div class="w-[19mm] shrink-0 flex flex-col items-center justify-center p-1 bg-white rounded-lg border border-emerald-600/30 shadow-2xs">
                        <div id="qrcode-{{ $st->id }}" class="w-[50px] h-[50px] flex items-center justify-center m-0.5"></div>
                        <span class="text-[6.2px] font-extrabold text-emerald-900 tracking-wider mt-1 uppercase leading-none">SCAN PRESENSI</span>
                    </div>
                </div>

                <!-- Footer Strip Sisi Depan: Barcode + Identitas Sekolah -->
                <div class="border-t border-amber-400 bg-gradient-to-r from-emerald-950 via-emerald-800 to-teal-950 px-2.5 py-1 flex items-center justify-between text-[6.8px] text-white">
                    <!-- Barcode look & NIS -->
                    <div class="flex items-center space-x-1 font-semibold text-emerald-100 tracking-wider">
                        <span class="tracking-tighter font-extrabold text-[8px] leading-none text-white">||| | |||| || |||</span>
                        <span>NO. REG : {{ $st->nis }}</span>
                    </div>

                    <!-- Badge Dokumen Resmi -->
                    <span class="px-1.5 py-0.2 rounded-full bg-amber-400/90 text-emerald-950 font-extrabold text-[6px]">
                        ★ DOKUMEN RESMI SEKOLAH ★
                    </span>

                    <!-- Label Wilayah -->
                    <span class="font-bold text-white uppercase tracking-tight">KABUPATEN BOGOR</span>
                </div>
            </div>
        </div>

        <!-- WRAPPER KARTU SISWA: SISI BELAKANG -->
        <div id="card-back-{{ $st->id }}" class="card-item-wrapper card-back-item hidden" data-name="{{ strtolower($st->name) }}" data-nis="{{ $st->nis }}">
            <div class="id-card-wrapper border border-slate-300 flex flex-col justify-between card-security-pattern mx-auto bg-white">
                
                <!-- Header Sisi Belakang -->
                <div class="bg-gradient-to-r from-emerald-950 via-emerald-800 to-teal-950 text-white px-3 py-1.5 flex items-center justify-between border-b border-amber-400">
                    <span class="text-[7.5px] font-bold uppercase tracking-wider text-amber-300">KETENTUAN PENGGUNAAN KARTU PELAJAR</span>
                    <span class="text-[7.5px] text-emerald-200 font-semibold">NIS: {{ $st->nis }}</span>
                </div>

                <!-- Isi Tata Tertib & Ketentuan -->
                <div class="px-3.5 py-1.5 space-y-0.5 text-slate-700 leading-tight">
                    <ol class="list-decimal list-inside space-y-0.5 text-[7.5px]">
                        <li>Kartu ini adalah identitas resmi siswa <strong>{{ $schoolSetting->school_name ?? 'SMK Darus Sa\'adah' }}</strong>.</li>
                        <li>Wajib dibawa setiap hari dan digunakan saat presensi harian (scan QR).</li>
                        <li>Dilarang keras memindahtangankan kartu presensi kepada siswa lain.</li>
                        <li>Apabila kartu hilang atau rusak, segera melapor ke bagian Tata Usaha sekolah.</li>
                    </ol>
                </div>

                <!-- Tanda Tangan & Stempel Resmi Kepala Sekolah -->
                <div class="px-3.5 pb-1.5 flex items-end justify-between">
                    <div class="text-[7px] text-slate-500 leading-tight max-w-[45%]">
                        <p class="font-bold text-slate-700">Alamat Sekolah:</p>
                        <p class="truncate">{{ $schoolAddress }}</p>
                    </div>

                    <!-- Blok Pejabat Pengesah -->
                    <div class="text-right text-[7px] text-slate-700 leading-tight relative">
                        <!-- Stempel bulat resmi sekolah -->
                        <div class="absolute -top-2 right-4 w-9 h-9 rounded-full border border-emerald-700/50 flex items-center justify-center text-[5.5px] font-bold uppercase text-emerald-800/60 transform -rotate-12 pointer-events-none">
                            STEMPEL
                        </div>
                        <p>{{ $schoolSetting->city ?? 'Kabupaten Bogor' }}, {{ now()->isoFormat('D MMMM Y') }}</p>
                        <p class="font-bold text-slate-900 mt-0.5">Kepala Sekolah,</p>
                        <div class="h-5"></div>
                        <p class="font-bold text-slate-900 underline">{{ $schoolSetting->principal_name ?? 'Drs. H. Mulyadi, M.Pd.' }}</p>
                        <p class="text-[6.5px] text-slate-500 font-medium">NIP. {{ $schoolSetting->principal_nip ?? '19720315 199802 1 002' }}</p>
                    </div>
                </div>

                <!-- Footer Strip Sisi Belakang -->
                <div class="border-t border-amber-400/80 bg-gradient-to-r from-emerald-950 via-emerald-800 to-teal-950 px-3 py-0.5 flex items-center justify-between text-[6.5px] text-emerald-100">
                    <span>KARTU IDENTITAS RESMI • SMK DARUS SA'ADAH</span>
                    <span>T.A. {{ $st->classroom->academicYear->year_name ?? '-' }}</span>
                </div>
            </div>
        </div>

        @empty
        <div class="col-span-2 p-12 text-center bg-white rounded-xl border border-slate-200 shadow-xs">
            <p class="text-slate-500 text-xs font-medium">Tidak ada data siswa untuk kelas yang dipilih.</p>
        </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
    let currentSide = 'front';

    function switchCardSide(side) {
        currentSide = side;
        const btnFront = document.getElementById('btnSideFront');
        const btnBack = document.getElementById('btnSideBack');
        const btnBoth = document.getElementById('btnSideBoth');

        // Reset classes
        [btnFront, btnBack, btnBoth].forEach(btn => {
            if (btn) {
                btn.className = 'px-3 py-1 rounded-md text-xs font-medium text-slate-600 hover:text-slate-800 transition';
            }
        });

        const activeClass = 'px-3 py-1 rounded-md text-xs font-semibold bg-white text-emerald-800 shadow-2xs transition';

        if (side === 'front') {
            btnFront.className = activeClass;
            document.querySelectorAll('.card-front-item').forEach(el => el.classList.remove('hidden'));
            document.querySelectorAll('.card-back-item').forEach(el => el.classList.add('hidden'));
        } else if (side === 'back') {
            btnBack.className = activeClass;
            document.querySelectorAll('.card-front-item').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.card-back-item').forEach(el => el.classList.remove('hidden'));
        } else if (side === 'both') {
            btnBoth.className = activeClass;
            document.querySelectorAll('.card-front-item').forEach(el => el.classList.remove('hidden'));
            document.querySelectorAll('.card-back-item').forEach(el => el.classList.remove('hidden'));
        }
    }

    function filterCards() {
        const query = (document.getElementById('cardSearchInput').value || '').toLowerCase().trim();
        document.querySelectorAll('.card-item-wrapper').forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const nis = card.getAttribute('data-nis') || '';
            const matches = name.includes(query) || nis.includes(query);
            
            if (!matches) {
                card.style.display = 'none';
            } else {
                card.style.display = '';
                if (currentSide === 'front' && card.classList.contains('card-back-item')) {
                    card.classList.add('hidden');
                } else if (currentSide === 'back' && card.classList.contains('card-front-item')) {
                    card.classList.add('hidden');
                }
            }
        });
    }

    // Render Offline QR Codes via QRCode.js
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($students as $st)
        (function() {
            const container = document.getElementById('qrcode-{{ $st->id }}');
            if (container) {
                container.innerHTML = '';
                new QRCode(container, {
                    text: "{{ $st->nis }}",
                    width: 52,
                    height: 52,
                    colorDark: "#064e3b",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.M
                });
            }
        })();
        @endforeach
    });
</script>
@endpush
@endsection
