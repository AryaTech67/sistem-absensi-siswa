<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Presensi Sesi - {{ $attendanceSession->classroom->name }} ({{ $attendanceSession->attendance_date->format('d/m/Y') }})</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0f172a;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            @page {
                size: A4 portrait;
                margin: 15mm 15mm 15mm 15mm;
            }
            body {
                background: white;
            }
        }
    </style>
</head>
<body class="bg-slate-100 p-4 md:p-8">
    @php $schoolSetting = \App\Models\SchoolSetting::get(); @endphp

    <!-- Floating Print Button Toolbar (Hilang saat cetak) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex items-center justify-between bg-white p-4 rounded-xl shadow-md border border-slate-200">
        <div class="flex items-center space-x-2 text-sm text-slate-600">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            <span class="font-medium">Siap dicetak ke printer atau Simpan sebagai PDF (A4).</span>
        </div>
        <div class="flex items-center space-x-2">
            <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs shadow-sm shadow-blue-500/20 transition flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Dokumen / PDF</span>
            </button>
            <button onclick="window.close()" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                Tutup
            </button>
        </div>
    </div>

    <!-- Halaman Dokumen Resmi Cetak -->
    <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 shadow-lg print:shadow-none print:p-0 rounded-xl print:rounded-none">
        
        <!-- KOP SURAT SEKOLAH -->
        <div class="border-b-2 border-slate-900 pb-4 mb-6 text-center">
            <h3 class="text-sm font-semibold tracking-wider uppercase text-slate-600">{{ $schoolSetting->agency_name }}</h3>
            <h1 class="text-xl font-black tracking-tight text-slate-900 uppercase mt-0.5">{{ $schoolSetting->school_name }}</h1>
            <p class="text-xs text-slate-600 mt-1">{{ $schoolSetting->school_address }} &bull; Telp. {{ $schoolSetting->school_phone }} &bull; Website: {{ $schoolSetting->school_website }}</p>
        </div>

        <!-- Judul Berita Acara -->
        <div class="text-center mb-6">
            <h2 class="text-base font-bold uppercase underline tracking-wide">BERITA ACARA & LEMBAR PRESENSI SISWA</h2>
            <p class="text-xs text-slate-600 mt-1">
                Hari/Tanggal: <strong>{{ $attendanceSession->attendance_date->isoFormat('dddd, D MMMM Y') }}</strong> &bull; Sesi: <strong>{{ $attendanceSession->session_name }}</strong>
            </p>
        </div>

        <!-- Metadata Sesi -->
        <div class="grid grid-cols-2 gap-4 text-xs mb-4">
            <div>
                <p><span class="w-28 inline-block text-slate-500">Rombongan Belajar</span>: <strong>{{ $attendanceSession->classroom->name }}</strong></p>
                <p><span class="w-28 inline-block text-slate-500">Tahun Ajaran</span>: {{ $attendanceSession->classroom->academicYear->year_name ?? '-' }}</p>
            </div>
            <div class="text-right">
                <p><span class="text-slate-500">Petugas Pencatat</span>: <strong>{{ $attendanceSession->creator->name ?? '-' }}</strong></p>
                <p><span class="text-slate-500">Waktu Rekam</span>: {{ $attendanceSession->created_at->format('d/m/Y H:i') }} WIB</p>
            </div>
        </div>

        <!-- Ringkasan Statistik Singkat -->
        <div class="grid grid-cols-5 gap-2 text-center text-xs mb-5 border border-slate-300 p-2.5 rounded-lg bg-slate-50">
            <div>
                <span class="text-slate-500 block text-[10px] uppercase font-bold">Total Siswa</span>
                <span class="font-bold text-slate-900 text-sm">{{ $counts['total'] }}</span>
            </div>
            <div>
                <span class="text-emerald-700 block text-[10px] uppercase font-bold">Hadir (H)</span>
                <span class="font-bold text-emerald-700 text-sm">{{ $counts['H'] }}</span>
            </div>
            <div>
                <span class="text-amber-700 block text-[10px] uppercase font-bold">Sakit (S)</span>
                <span class="font-bold text-amber-700 text-sm">{{ $counts['S'] }}</span>
            </div>
            <div>
                <span class="text-blue-700 block text-[10px] uppercase font-bold">Izin (I)</span>
                <span class="font-bold text-blue-700 text-sm">{{ $counts['I'] }}</span>
            </div>
            <div>
                <span class="text-rose-700 block text-[10px] uppercase font-bold">Alpa (A)</span>
                <span class="font-bold text-rose-700 text-sm">{{ $counts['A'] }}</span>
            </div>
        </div>

        <!-- Tabel Daftar Siswa -->
        <table class="w-full text-left border-collapse border border-slate-400 text-xs mb-6">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-400 text-center font-bold">
                    <th class="border border-slate-400 py-2 px-2 w-10">No</th>
                    <th class="border border-slate-400 py-2 px-3 w-24">NIS</th>
                    <th class="border border-slate-400 py-2 px-3 text-left">Nama Siswa</th>
                    <th class="border border-slate-400 py-2 px-2 w-14">L/P</th>
                    <th class="border border-slate-400 py-2 px-3 w-24">Status</th>
                    <th class="border border-slate-400 py-2 px-3 text-left">Catatan / Alasan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendanceSession->attendances as $idx => $att)
                <tr class="border-b border-slate-300">
                    <td class="border border-slate-300 py-1.5 px-2 text-center">{{ $loop->iteration }}</td>
                    <td class="border border-slate-300 py-1.5 px-3 text-xs">{{ $att->student->nis ?? '-' }}</td>
                    <td class="border border-slate-300 py-1.5 px-3 font-medium">{{ $att->student->name ?? '-' }}</td>
                    <td class="border border-slate-300 py-1.5 px-2 text-center">{{ $att->student->gender ?? '-' }}</td>
                    <td class="border border-slate-300 py-1.5 px-3 text-center font-bold">
                        @if($att->status == 'H')
                            <span class="text-emerald-700 font-bold">Hadir</span>
                        @elseif($att->status == 'T')
                            <span class="text-amber-700 font-bold">Terlambat</span>
                        @elseif($att->status == 'S')
                            <span class="text-yellow-700 font-bold">Sakit</span>
                        @elseif($att->status == 'I')
                            <span class="text-blue-700 font-bold">Izin</span>
                        @else
                            <span class="text-rose-700 font-bold">Alpa</span>
                        @endif
                    </td>
                    <td class="border border-slate-300 py-1.5 px-3 text-slate-600 text-[11px]">{{ $att->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Tanda Tangan Resmi -->
        <div class="mt-8 text-xs">
            <div class="flex justify-between items-start">
                <div class="w-64">
                    <p class="font-semibold text-slate-600 mb-1">Keterangan:</p>
                    <ul class="text-[11px] text-slate-500 space-y-0.5">
                        <li><strong>H (Hadir)</strong>: Hadir mengikuti KBM</li>
                        <li><strong>T (Terlambat)</strong>: Hadir terlambat</li>
                        <li><strong>S (Sakit)</strong>: Ada surat keterangan dokter/wali</li>
                        <li><strong>I (Izin)</strong>: Ada surat/pemberitahuan resmi</li>
                        <li><strong>A (Alpa)</strong>: Tidak hadir tanpa keterangan</li>
                    </ul>
                </div>

                <div class="text-center w-64">
                    <p>{{ $schoolSetting->city ?? 'Kota Pendidikan' }}, {{ $attendanceSession->attendance_date->isoFormat('D MMMM Y') }}</p>
                    <p class="mt-1 font-semibold">Petugas Absensi / Wali Kelas,</p>
                    <div class="h-20"></div>
                    <p class="font-bold underline uppercase">{{ ($attendanceSession->classroom && $attendanceSession->classroom->waliKelas) ? $attendanceSession->classroom->waliKelas->name : ($attendanceSession->creator->name ?? auth()->user()->name) }}</p>
                    <p class="text-slate-500 text-xs">NIP. {{ ($attendanceSession->classroom && $attendanceSession->classroom->waliKelas && $attendanceSession->classroom->waliKelas->nip) ? $attendanceSession->classroom->waliKelas->nip : '-' }}</p>
                </div>
            </div>

            <div class="mt-6 text-center">
                <p class="font-semibold">Mengetahui,</p>
                <p class="font-semibold">Kepala Sekolah {{ $schoolSetting->school_name }},</p>
                <div class="h-20"></div>
                <p class="font-bold underline uppercase">{{ $schoolSetting->principal_name }}</p>
                <p class="text-slate-500 text-xs">NIP. {{ $schoolSetting->principal_nip ?? '-' }}</p>
            </div>
        </div>
    </div>

</body>
</html>
