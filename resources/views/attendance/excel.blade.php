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
        .badge-h { background-color: #d1fae5; color: #065f46; font-weight: bold; text-align: center; border: 1px solid #cccccc; }
        .badge-s { background-color: #fef3c7; color: #92400e; font-weight: bold; text-align: center; border: 1px solid #cccccc; }
        .badge-i { background-color: #dbeafe; color: #1e40af; font-weight: bold; text-align: center; border: 1px solid #cccccc; }
        .badge-a { background-color: #ffe4e6; color: #9f1239; font-weight: bold; text-align: center; border: 1px solid #cccccc; }
        .stat-header { background-color: #f1f5f9; font-weight: bold; border: 1px solid #94a3b8; text-align: center; }
        .stat-val { font-weight: bold; border: 1px solid #94a3b8; text-align: center; }
    </style>
</head>
<body>
    @php $schoolSetting = \App\Models\SchoolSetting::get(); @endphp
    <table>
        <tr>
            <td colspan="6" class="school">{{ $schoolSetting->school_name }}</td>
        </tr>
        <tr>
            <td colspan="6" class="title">LEMBAR PRESENSI KEHADIRAN SISWA</td>
        </tr>
        <tr>
            <td colspan="6" class="sub-title">Hari/Tanggal: {{ $attendanceSession->attendance_date->isoFormat('dddd, D MMMM Y') }} | Sesi: {{ $attendanceSession->session_name }}</td>
        </tr>
        <tr><td colspan="6"></td></tr>
        <tr>
            <td colspan="2"><strong>Kelas / Rombel:</strong> {{ $attendanceSession->classroom->name }}</td>
            <td colspan="4" style="text-align: right;"><strong>Petugas Pencatat:</strong> {{ $attendanceSession->creator->name ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Tahun Ajaran:</strong> {{ $attendanceSession->classroom->academicYear->year_name ?? '-' }}</td>
            <td colspan="4" style="text-align: right;"><strong>Waktu Input:</strong> {{ $attendanceSession->created_at->format('d/m/Y H:i') }} WIB</td>
        </tr>
        <tr><td colspan="6"></td></tr>

        <!-- Ringkasan Statistik -->
        <tr>
            <td class="stat-header">Total Siswa</td>
            <td class="stat-header">Hadir (H)</td>
            <td class="stat-header">Sakit (S)</td>
            <td class="stat-header">Izin (I)</td>
            <td class="stat-header">Alpa (A)</td>
            <td class="stat-header">% Kehadiran</td>
        </tr>
        <tr>
            <td class="stat-val">{{ $counts['total'] }}</td>
            <td class="stat-val" style="color: #059669;">{{ $counts['H'] }}</td>
            <td class="stat-val" style="color: #d97706;">{{ $counts['S'] }}</td>
            <td class="stat-val" style="color: #2563eb;">{{ $counts['I'] }}</td>
            <td class="stat-val" style="color: #e11d48;">{{ $counts['A'] }}</td>
            <td class="stat-val" style="color: #2563eb;">{{ $counts['percentage'] }}%</td>
        </tr>
        <tr><td colspan="6"></td></tr>

        <!-- Header Tabel -->
        <tr>
            <th class="table-header" style="width: 40px;">No</th>
            <th class="table-header" style="width: 120px;">NIS</th>
            <th class="table-header" style="width: 250px;">Nama Lengkap Siswa</th>
            <th class="table-header" style="width: 60px;">L/P</th>
            <th class="table-header" style="width: 100px;">Status</th>
            <th class="table-header" style="width: 200px;">Catatan / Keterangan</th>
        </tr>

        <!-- Body Data -->
        @foreach($attendanceSession->attendances as $idx => $att)
        <tr>
            <td class="cell-center">{{ $loop->iteration }}</td>
            <td class="cell-center" style="mso-number-format:'\@';">{{ $att->student->nis ?? '-' }}</td>
            <td class="cell-left">{{ $att->student->name ?? '-' }}</td>
            <td class="cell-center">{{ $att->student->gender ?? '-' }}</td>
            @if($att->status == 'H')
                <td class="badge-h">Hadir</td>
            @elseif($att->status == 'S')
                <td class="badge-s">Sakit</td>
            @elseif($att->status == 'I')
                <td class="badge-i">Izin</td>
            @else
                <td class="badge-a">Alpa</td>
            @endif
            <td class="cell-left">{{ $att->notes ?? '-' }}</td>
        </tr>
        @endforeach

        <tr><td colspan="6"></td></tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="3" style="text-align: center;">{{ $schoolSetting->city ?? 'Kota Pendidikan' }}, {{ $attendanceSession->attendance_date->isoFormat('D MMMM Y') }}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="3" style="text-align: center;">Petugas Absensi / Wali Kelas,</td>
        </tr>
        <tr><td colspan="6" style="height: 40px;"></td></tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="3" style="text-align: center; font-weight: bold; text-decoration: underline;">{{ ($attendanceSession->classroom && $attendanceSession->classroom->waliKelas) ? $attendanceSession->classroom->waliKelas->name : ($attendanceSession->creator->name ?? auth()->user()->name) }}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="3" style="text-align: center; font-size: 9pt; color: #64748b;">NIP. {{ ($attendanceSession->classroom && $attendanceSession->classroom->waliKelas && $attendanceSession->classroom->waliKelas->nip) ? $attendanceSession->classroom->waliKelas->nip : '-' }}</td>
        </tr>
        <tr><td colspan="6"></td></tr>
        <tr>
            <td colspan="6" style="text-align: center;">Mengetahui,</td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center;">Kepala Sekolah {{ $schoolSetting->school_name }},</td>
        </tr>
        <tr><td colspan="6" style="height: 40px;"></td></tr>
        <tr>
            <td colspan="6" style="text-align: center; font-weight: bold; text-decoration: underline;">{{ $schoolSetting->principal_name }}</td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center; font-size: 9pt; color: #64748b;">NIP. {{ $schoolSetting->principal_nip ?? '-' }}</td>
        </tr>
    </table>
</body>
</html>
