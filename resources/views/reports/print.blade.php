<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Kehadiran Siswa - {{ $selectedClassroom->name ?? 'Semua Kelas' }}</title>
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
                <span>Cetak Dokumen</span>
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

        <!-- Judul Laporan -->
        <div class="text-center mb-6">
            <h2 class="text-base font-bold uppercase underline tracking-wide">LAPORAN REKAPITULASI PRESENSI SISWA</h2>
            <p class="text-xs text-slate-600 mt-1">
                Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</strong>
            </p>
        </div>

        <!-- Metadata Dokumen -->
        <div class="grid grid-cols-2 gap-4 text-xs mb-4">
            <div>
                <p><span class="w-24 inline-block text-slate-500">Rombel / Kelas</span>: <strong>{{ $selectedClassroom->name ?? 'Semua Rombel' }}</strong></p>
                <p><span class="w-24 inline-block text-slate-500">Tahun Ajaran</span>: {{ $selectedClassroom->academicYear->year_name ?? '-' }}</p>
            </div>
            <div class="text-right">
                <p><span class="text-slate-500">Tanggal Pembuatan</span>: {{ $printDate }}</p>
                <p><span class="text-slate-500">Dicetak Oleh</span>: {{ auth()->user()->name }}</p>
            </div>
        </div>

        <!-- Tabel Laporan Absensi -->
        <table class="w-full text-left border-collapse border border-slate-400 text-xs mb-6">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-400 text-center font-bold">
                    <th class="border border-slate-400 py-2 px-2 w-10">No</th>
                    <th class="border border-slate-400 py-2 px-3 w-24">NIS</th>
                    <th class="border border-slate-400 py-2 px-3 text-left">Nama Lengkap Siswa</th>
                    <th class="border border-slate-400 py-2 px-2 w-16">Kelas</th>
                    <th class="border border-slate-400 py-2 px-2 w-10">H</th>
                    <th class="border border-slate-400 py-2 px-2 w-10">T</th>
                    <th class="border border-slate-400 py-2 px-2 w-10">S</th>
                    <th class="border border-slate-400 py-2 px-2 w-10">I</th>
                    <th class="border border-slate-400 py-2 px-2 w-10">A</th>
                    <th class="border border-slate-400 py-2 px-2 w-14">Total</th>
                    <th class="border border-slate-400 py-2 px-2 w-16">% Hadir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recapList as $idx => $row)
                <tr class="border-b border-slate-300">
                    <td class="border border-slate-300 py-1.5 px-2 text-center">{{ $loop->iteration }}</td>
                    <td class="border border-slate-300 py-1.5 px-3 text-xs">{{ $row['student']->nis }}</td>
                    <td class="border border-slate-300 py-1.5 px-3 font-medium">{{ $row['student']->name }}</td>
                    <td class="border border-slate-300 py-1.5 px-2 text-center">{{ $row['student']->classroom->name ?? '-' }}</td>
                    <td class="border border-slate-300 py-1.5 px-2 text-center font-bold">{{ $row['hadir'] }}</td>
                    <td class="border border-slate-300 py-1.5 px-2 text-center font-bold text-amber-700">{{ $row['terlambat'] ?? 0 }}</td>
                    <td class="border border-slate-300 py-1.5 px-2 text-center">{{ $row['sakit'] }}</td>
                    <td class="border border-slate-300 py-1.5 px-2 text-center">{{ $row['izin'] }}</td>
                    <td class="border border-slate-300 py-1.5 px-2 text-center {{ $row['alpa'] > 0 ? 'font-bold text-rose-700' : '' }}">{{ $row['alpa'] }}</td>
                    <td class="border border-slate-300 py-1.5 px-2 text-center font-semibold">{{ $row['total'] }}</td>
                    <td class="border border-slate-300 py-1.5 px-2 text-center font-bold">{{ $row['percentage'] }}%</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-slate-50 font-bold border-t border-slate-400 text-center">
                    <td colspan="4" class="border border-slate-400 py-2 px-3 text-right">JUMLAH TOTAL:</td>
                    <td class="border border-slate-400 py-2 px-2">{{ $summary['hadir'] }}</td>
                    <td class="border border-slate-400 py-2 px-2 text-amber-700">{{ $summary['terlambat'] ?? 0 }}</td>
                    <td class="border border-slate-400 py-2 px-2">{{ $summary['sakit'] }}</td>
                    <td class="border border-slate-400 py-2 px-2">{{ $summary['izin'] }}</td>
                    <td class="border border-slate-400 py-2 px-2">{{ $summary['alpa'] }}</td>
                    <td class="border border-slate-400 py-2 px-2">{{ $summary['total'] }}</td>
                    <td class="border border-slate-400 py-2 px-2">{{ $summary['overall_percentage'] }}%</td>
                </tr>
            </tfoot>
        </table>

        <!-- Keterangan & Tanda Tangan Resmi -->
        <div class="mt-8 text-xs">
            <div class="flex justify-between items-start">
                <div class="w-64">
                    <p class="font-semibold text-slate-600 mb-1">Keterangan Status:</p>
                    <ul class="text-[11px] text-slate-500 space-y-0.5">
                        <li><strong>H</strong> : Hadir tepat waktu</li>
                        <li><strong>T</strong> : Presensi terlambat</li>
                        <li><strong>S</strong> : Sakit dengan surat keterangan</li>
                        <li><strong>I</strong> : Izin keperluan mendesak</li>
                        <li><strong>A</strong> : Alpa / Tanpa keterangan</li>
                    </ul>
                </div>

                <div class="text-center w-64">
                    <p>{{ $schoolSetting->city ?? 'Kota Pendidikan' }}, {{ $printDate }}</p>
                    <p class="mt-1 font-semibold">Petugas Absensi / Wali Kelas,</p>
                    <div class="h-20"></div>
                    <p class="font-bold underline uppercase">{{ ($selectedClassroom && $selectedClassroom->waliKelas) ? $selectedClassroom->waliKelas->name : auth()->user()->name }}</p>
                    <p class="text-slate-500 text-xs">NIP. {{ ($selectedClassroom && $selectedClassroom->waliKelas && $selectedClassroom->waliKelas->nip) ? $selectedClassroom->waliKelas->nip : '-' }}</p>
                </div>
            </div>

            <div class="mt-8 text-center">
                <p class="font-semibold">Mengetahui,</p>
                <p class="font-semibold">Kepala Sekolah,</p>
                <div class="h-20"></div>
                <p class="font-bold underline uppercase">{{ $schoolSetting->principal_name ?? 'Kepala Sekolah' }}</p>
                <p class="text-slate-500 text-xs">NIP. {{ $schoolSetting->principal_nip ?? '-' }}</p>
            </div>
        </div>
    </div>

</body>
</html>
