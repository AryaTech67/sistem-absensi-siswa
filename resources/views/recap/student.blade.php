@extends('layouts.app')

@section('title', 'Rekap Kehadiran per Siswa')

@section('breadcrumbs')
    <a href="{{ route('dashboard') }}" class="hover:text-slate-800 transition">Dashboard</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-500">Rekapitulasi</span>
    <span class="text-slate-400">/</span>
    <span class="font-medium text-slate-800">Per Siswa</span>
@endsection

@section('content')
<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Rekap Kehadiran Siswa</h1>
            <p class="text-xs text-slate-500 mt-1">Laporan riwayat kehadiran individual siswa secara lengkap dan persentasenya.</p>
        </div>
        <a href="{{ route('recap.class') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium text-xs shadow-xs transition self-start sm:self-auto">
            <span>Lihat Rekap per Rombel</span>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </a>
    </div>

    <!-- Filter Form Siswa & Periode -->
    <div class="bg-white p-4 sm:p-5 rounded-xl border border-slate-200/90 shadow-xs">
        <form method="GET" action="{{ route('recap.student') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                <div>
                    <label for="classroom_id" class="block text-xs font-semibold text-slate-700 mb-1">Filter Rombel</label>
                    <select id="classroom_id" name="classroom_id" onchange="this.form.submit()"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition bg-white">
                        <option value="">Semua Rombel</option>
                        @foreach($classrooms as $cls)
                        <option value="{{ $cls->id }}" {{ $selectedClassroomId == $cls->id ? 'selected' : '' }}>
                            {{ $cls->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="student_id" class="block text-xs font-semibold text-slate-700 mb-1">Pilih Siswa</label>
                    <select id="student_id" name="student_id" onchange="this.form.submit()"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition bg-white">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($studentsList as $st)
                        <option value="{{ $st->id }}" {{ ($selectedStudentId == $st->id || (isset($selectedStudent) && $selectedStudent->id == $st->id)) ? 'selected' : '' }}>
                            {{ $st->nis }} - {{ $st->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="start_date" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition bg-white">
                </div>

                <div>
                    <label for="end_date" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Sampai</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition bg-white">
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                @if($selectedStudent || $startDate || $endDate)
                <a href="{{ route('recap.student') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition">
                    Reset Filter
                </a>
                @endif
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-lg shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Tampilkan Rekapitulasi</span>
                </button>
            </div>
        </form>
    </div>

    @if($selectedStudent)
    <!-- Profil & Ringkasan Siswa -->
    <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs p-5 md:p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between pb-5 border-b border-slate-100 gap-4">
            <div class="flex items-center space-x-3.5">
                <div class="w-12 h-12 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-center font-bold text-lg">
                    {{ strtoupper(substr($selectedStudent->name, 0, 2)) }}
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">{{ $selectedStudent->name }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">NIS: <span class="font-semibold text-slate-800">{{ $selectedStudent->nis }}</span> &bull; Kelas: <span class="font-semibold text-slate-800">{{ $selectedStudent->classroom->name ?? '-' }}</span></p>
                </div>
            </div>

            <!-- Persentase Kehadiran -->
            <div class="text-left md:text-right bg-slate-50 md:bg-transparent p-3 md:p-0 rounded-lg">
                <p class="text-xs text-slate-500 font-medium">Tingkat Kehadiran Siswa</p>
                <div class="flex items-center md:justify-end space-x-2 mt-1">
                    <span class="text-2xl font-extrabold {{ $summary['percentage'] >= 85 ? 'text-emerald-600' : ($summary['percentage'] >= 75 ? 'text-amber-500' : 'text-rose-600') }}">
                        {{ $summary['percentage'] }}%
                    </span>
                    <span class="text-[10px] px-2 py-0.5 rounded font-bold uppercase {{ $summary['percentage'] >= 85 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                        {{ $summary['percentage'] >= 85 ? 'Sangat Baik' : 'Perlu Perhatian' }}
                    </span>
                </div>
                <p class="text-[10px] text-slate-400 mt-0.5 font-medium">(Hadir + Terlambat) / Total &times; 100%</p>
            </div>
        </div>

        <!-- Kartu Metrik H, T, S, I, A, Total -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 pt-5">
            <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200">
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Hadir (H)</p>
                <p class="text-xl font-bold text-emerald-600 mt-1">{{ $summary['hadir'] }} <span class="text-xs font-normal text-slate-500">Hari</span></p>
            </div>
            <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200">
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Terlambat (T)</p>
                <p class="text-xl font-bold text-amber-600 mt-1">{{ $summary['terlambat'] }} <span class="text-xs font-normal text-slate-500">Hari</span></p>
            </div>
            <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200">
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Sakit (S)</p>
                <p class="text-xl font-bold text-yellow-600 mt-1">{{ $summary['sakit'] }} <span class="text-xs font-normal text-slate-500">Hari</span></p>
            </div>
            <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200">
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Izin (I)</p>
                <p class="text-xl font-bold text-sky-600 mt-1">{{ $summary['izin'] }} <span class="text-xs font-normal text-slate-500">Hari</span></p>
            </div>
            <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200">
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Alpa (A)</p>
                <p class="text-xl font-bold text-rose-600 mt-1">{{ $summary['alpa'] }} <span class="text-xs font-normal text-slate-500">Hari</span></p>
            </div>
            <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200">
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider">Total Hari</p>
                <p class="text-xl font-bold text-slate-800 mt-1">{{ $summary['total'] }} <span class="text-xs font-normal text-slate-500">Hari</span></p>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Kehadiran -->
    <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-3.5 bg-slate-50/70 border-b border-slate-200/80 font-bold text-xs uppercase tracking-wide text-slate-800">
            Riwayat Kehadiran Siswa per Tanggal
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs min-w-[600px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                        <th class="py-2.5 px-4 w-12 text-center">No</th>
                        <th class="py-2.5 px-4 w-44">Tanggal</th>
                        <th class="py-2.5 px-4">Nama Siswa</th>
                        <th class="py-2.5 px-4 w-28 text-center">Status</th>
                        <th class="py-2.5 px-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($attendances as $idx => $att)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-2.5 px-4 text-center text-xs text-slate-400">
                            {{ $loop->iteration }}
                        </td>
                        <td class="py-2.5 px-4 text-xs text-slate-700 whitespace-nowrap font-medium">
                            {{ $att->attendanceSession->attendance_date->isoFormat('dddd, D MMM Y') }}
                            <span class="text-[10px] text-slate-400 block font-normal">{{ $att->attendanceSession->classroom->name ?? '-' }} &bull; Sesi {{ $att->attendanceSession->session_name ?? 'Pagi' }}</span>
                        </td>
                        <td class="py-2.5 px-4 font-semibold text-slate-900">
                            {{ $selectedStudent->name }}
                        </td>
                        <td class="py-2.5 px-4 text-center">
                            @if($att->status == 'H')
                            <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Hadir
                            </span>
                            @elseif($att->status == 'T')
                            <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                Terlambat
                            </span>
                            @elseif($att->status == 'S')
                            <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                Sakit
                            </span>
                            @elseif($att->status == 'I')
                            <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-semibold bg-sky-100 text-sky-800 border border-sky-200">
                                Izin
                            </span>
                            @elseif($att->status == 'A')
                            <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-semibold bg-rose-100 text-rose-800 border border-rose-200">
                                Alpa
                            </span>
                            @endif
                        </td>
                        <td class="py-2.5 px-4 text-slate-600">
                            @if($att->status == 'T')
                                <span class="font-medium text-amber-800">{{ $att->notes ?? 'Presensi Masuk Terlambat' }}</span>
                                @if(!empty($att->missed_periods))
                                    <span class="text-[11px] text-amber-700 block">Terlewat: {{ $att->missed_periods }}</span>
                                @endif
                                @if(!empty($att->compensation_task))
                                    <span class="text-[11px] text-slate-500 block">Tugas: {{ $att->compensation_task }}</span>
                                @endif
                            @else
                                {{ $att->notes ?? '-' }}
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-slate-400 text-xs">
                            Belum ada catatan absensi untuk siswa ini pada periode yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="p-10 text-center bg-white rounded-xl border border-slate-200/90 shadow-xs">
        <div class="w-12 h-12 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <p class="font-bold text-slate-900 text-sm">Pilih Siswa Terlebih Dahulu</p>
        <p class="text-slate-500 text-xs mt-1 max-w-sm mx-auto leading-relaxed">Pilih nama atau nomor induk siswa melalui filter di atas untuk melihat rincian riwayat kehadirannya.</p>
    </div>
    @endif
</div>
@endsection
