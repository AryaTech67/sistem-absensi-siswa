<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .title { font-size: 14pt; font-weight: bold; text-align: center; }
        .sub-title { font-size: 11pt; text-align: center; font-weight: bold; }
        .school { font-size: 12pt; text-align: center; font-weight: bold; color: #1e3a8a; }
        .table-header { background-color: #2563eb; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; }
        .cell-center { text-align: center; border: 1px solid #cccccc; }
        .cell-left { text-align: left; border: 1px solid #cccccc; }
        .cell-bold { font-weight: bold; }
        .cell-alpa { text-align: center; border: 1px solid #cccccc; font-weight: bold; color: #e11d48; }
        .stat-header { background-color: #f1f5f9; font-weight: bold; border: 1px solid #94a3b8; text-align: center; }
        .stat-val { font-weight: bold; border: 1px solid #94a3b8; text-align: center; }
        .footer-row { background-color: #e2e8f0; font-weight: bold; border: 1px solid #000000; }
    </style>
</head>
<body>
    @php $schoolSetting = \App\Models\SchoolSetting::get(); @endphp
    <table>
        <tr>
            <td colspan="11" class="school">{{ $schoolSetting->school_name }}</td>
        </tr>
        <tr>
            <td colspan="11" class="title">LAPORAN REKAPITULASI PRESENSI KEHADIRAN SISWA</td>
        </tr>
        <tr>
            <td colspan="11" class="sub-title">Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</td>
        </tr>
        <tr><td colspan="11"></td></tr>
        <tr>
            <td colspan="4"><strong>Rombel / Kelas:</strong> {{ $selectedClassroom->name ?? 'Semua Kelas' }}</td>
            <td colspan="7" style="text-align: right;"><strong>Tanggal Cetak:</strong> {{ $printDate }}</td>
        </tr>
        <tr>
            <td colspan="4"><strong>Tahun Ajaran:</strong> {{ $selectedClassroom->academicYear->year_name ?? '-' }}</td>
            <td colspan="7" style="text-align: right;"><strong>Dicetak Oleh:</strong> {{ auth()->user()->name }}</td>
        </tr>
        <tr><td colspan="11"></td></tr>

        <!-- Ringkasan Statistik -->
        <tr>
            <th colspan="2" class="stat-header">Total Kehadiran (H)</th>
            <th colspan="2" class="stat-header">Total Terlambat (T)</th>
            <th colspan="2" class="stat-header">Total Sakit (S)</th>
            <th colspan="2" class="stat-header">Total Izin (I)</th>
            <th colspan="2" class="stat-header">Total Alpa (A)</th>
            <th colspan="1" class="stat-header">Rata-rata Kehadiran</th>
        </tr>
        <tr>
            <td colspan="2" class="stat-val" style="color: #059669;">{{ $summary['hadir'] }}</td>
            <td colspan="2" class="stat-val" style="color: #d97706;">{{ $summary['terlambat'] ?? 0 }}</td>
            <td colspan="2" class="stat-val" style="color: #eab308;">{{ $summary['sakit'] }}</td>
            <td colspan="2" class="stat-val" style="color: #2563eb;">{{ $summary['izin'] }}</td>
            <td colspan="2" class="stat-val" style="color: #e11d48;">{{ $summary['alpa'] }}</td>
            <td colspan="1" class="stat-val" style="color: #2563eb;">{{ $summary['overall_percentage'] }}%</td>
        </tr>
        <tr><td colspan="11"></td></tr>

        <!-- Header Tabel -->
        <tr>
            <th class="table-header" style="width: 40px;">No</th>
            <th class="table-header" style="width: 120px;">NIS</th>
            <th class="table-header" style="width: 250px;">Nama Lengkap Siswa</th>
            <th class="table-header" style="width: 100px;">Kelas</th>
            <th class="table-header" style="width: 70px;">Hadir (H)</th>
            <th class="table-header" style="width: 70px;">Terlambat (T)</th>
            <th class="table-header" style="width: 70px;">Sakit (S)</th>
            <th class="table-header" style="width: 70px;">Izin (I)</th>
            <th class="table-header" style="width: 70px;">Alpa (A)</th>
            <th class="table-header" style="width: 90px;">Total Pertemuan</th>
            <th class="table-header" style="width: 100px;">% Kehadiran</th>
        </tr>

        <!-- Body Data -->
        @foreach($recapList as $idx => $row)
        <tr>
            <td class="cell-center">{{ $loop->iteration }}</td>
            <td class="cell-center" style="mso-number-format:'\@';">{{ $row['student']->nis }}</td>
            <td class="cell-left">{{ $row['student']->name }}</td>
            <td class="cell-center">{{ $row['student']->classroom->name ?? '-' }}</td>
            <td class="cell-center cell-bold" style="color: #059669;">{{ $row['hadir'] }}</td>
            <td class="cell-center cell-bold" style="color: #d97706;">{{ $row['terlambat'] ?? 0 }}</td>
            <td class="cell-center">{{ $row['sakit'] }}</td>
            <td class="cell-center">{{ $row['izin'] }}</td>
            <td class="{{ $row['alpa'] > 0 ? 'cell-alpa' : 'cell-center' }}">{{ $row['alpa'] }}</td>
            <td class="cell-center cell-bold">{{ $row['total'] }}</td>
            <td class="cell-center cell-bold">{{ $row['percentage'] }}%</td>
        </tr>
        @endforeach

        <!-- Footer Total -->
        <tr>
            <td colspan="4" class="footer-row" style="text-align: right;">JUMLAH TOTAL:</td>
            <td class="footer-row cell-center">{{ $summary['hadir'] }}</td>
            <td class="footer-row cell-center" style="color: #d97706;">{{ $summary['terlambat'] ?? 0 }}</td>
            <td class="footer-row cell-center">{{ $summary['sakit'] }}</td>
            <td class="footer-row cell-center">{{ $summary['izin'] }}</td>
            <td class="footer-row cell-center" style="color: #e11d48;">{{ $summary['alpa'] }}</td>
            <td class="footer-row cell-center">{{ $summary['total'] }}</td>
            <td class="footer-row cell-center">{{ $summary['overall_percentage'] }}%</td>
        </tr>

        <tr><td colspan="11"></td></tr>
        <tr>
            <td colspan="6"></td>
            <td colspan="4" style="text-align: center;">{{ $schoolSetting->city ?? 'Kota Pendidikan' }}, {{ $printDate }}</td>
        </tr>
        <tr>
            <td colspan="6"></td>
            <td colspan="4" style="text-align: center;">Wali Kelas / Petugas Absensi,</td>
        </tr>
        <tr><td colspan="10" style="height: 40px;"></td></tr>
        <tr>
            <td colspan="6"></td>
            <td colspan="4" style="text-align: center; font-weight: bold; text-decoration: underline;">{{ ($selectedClassroom && $selectedClassroom->waliKelas) ? $selectedClassroom->waliKelas->name : auth()->user()->name }}</td>
        </tr>
        <tr>
            <td colspan="6"></td>
            <td colspan="4" style="text-align: center; font-size: 9pt; color: #64748b;">NIP. {{ ($selectedClassroom && $selectedClassroom->waliKelas && $selectedClassroom->waliKelas->nip) ? $selectedClassroom->waliKelas->nip : '-' }}</td>
        </tr>
        <tr><td colspan="10"></td></tr>
        <tr>
            <td colspan="10" style="text-align: center;">Mengetahui,</td>
        </tr>
        <tr>
            <td colspan="10" style="text-align: center;">Kepala Sekolah {{ $schoolSetting->school_name }},</td>
        </tr>
        <tr><td colspan="10" style="height: 40px;"></td></tr>
        <tr>
            <td colspan="10" style="text-align: center; font-weight: bold; text-decoration: underline;">{{ $schoolSetting->principal_name }}</td>
        </tr>
        <tr>
            <td colspan="10" style="text-align: center; font-size: 9pt; color: #64748b;">NIP. {{ $schoolSetting->principal_nip ?? '-' }}</td>
        </tr>
    </table>
</body>
</html>
