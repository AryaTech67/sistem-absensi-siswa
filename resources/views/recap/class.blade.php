@extends('layouts.app')

@section('title', 'Rekap Kehadiran per Rombel')

@section('breadcrumbs')
    <a href="{{ route('dashboard') }}" class="hover:text-slate-800 transition">Dashboard</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-500">Rekapitulasi</span>
    <span class="text-slate-400">/</span>
    <span class="font-medium text-slate-800">Per Rombel</span>
@endsection

@section('content')
<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Rekapitulasi Presensi per Rombongan Belajar</h1>
            <p class="text-xs text-slate-500 mt-1">Ringkasan kehadiran kolektif seluruh siswa dalam satu kelas.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 shrink-0 self-start sm:self-auto">
            @if($selectedClassroom)
            <a href="{{ route('reports.print', ['classroom_id' => $selectedClassroomId, 'start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank"
                class="px-3.5 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-medium text-xs shadow-xs transition flex items-center space-x-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak PDF</span>
            </a>
            <a href="{{ route('recap.class.export-excel', ['classroom_id' => $selectedClassroomId, 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                class="px-3.5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs shadow-xs transition flex items-center space-x-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Ekspor Excel (.xls)</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-4 sm:p-5 rounded-xl border border-slate-200/90 shadow-xs">
        <form method="GET" action="{{ route('recap.class') }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
                <div>
                    <label for="classroom_id" class="block text-xs font-semibold text-slate-700 mb-1">Pilih Rombel</label>
                    <select id="classroom_id" name="classroom_id" onchange="this.form.submit()"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition bg-white">
                        @foreach($classrooms as $cls)
                        <option value="{{ $cls->id }}" {{ $selectedClassroomId == $cls->id ? 'selected' : '' }}>
                            {{ $cls->name }} ({{ $cls->academicYear->year_name ?? '-' }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="start_date" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" value="{{ $startDate }}"
                        class="w-full px-2.5 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition bg-white">
                </div>

                <div>
                    <label for="end_date" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Sampai</label>
                    <input type="date" id="end_date" name="end_date" value="{{ $endDate }}"
                        class="w-full px-2.5 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition bg-white">
                </div>

                <div>
                    <label for="search" class="block text-xs font-semibold text-slate-700 mb-1">Cari Nama / NIS</label>
                    <input type="text" id="search" name="search" value="{{ $search }}" placeholder="Ketik nama atau NIS..."
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                @if($startDate || $endDate || $search)
                <a href="{{ route('recap.class', ['classroom_id' => $selectedClassroomId]) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition">
                    Reset Filter
                </a>
                @endif
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-lg shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Terapkan Filter</span>
                </button>
            </div>
        </form>
    </div>

    @if($selectedClassroom)
    <!-- Ringkasan Kelas 6 Kolom -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block">Total Siswa</span>
            <p class="text-xl sm:text-2xl font-bold text-slate-900 mt-1">{{ $recapStudents->count() }}</p>
            <span class="text-[10px] text-slate-400">Terdaftar aktif</span>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block">Total Sesi</span>
            <p class="text-xl sm:text-2xl font-bold text-slate-900 mt-1">{{ $classAverages['total_sesi'] }} <span class="text-xs font-normal text-slate-400">Hari</span></p>
            <span class="text-[10px] text-slate-400">Dalam periode</span>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-semibold text-emerald-700 uppercase tracking-wider block">Tepat Waktu</span>
            <p class="text-xl sm:text-2xl font-bold text-emerald-600 mt-1">{{ $classAverages['total_hadir'] }}</p>
            <span class="text-[10px] text-slate-400">Hadir tepat waktu</span>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-semibold text-amber-700 uppercase tracking-wider block">Terlambat</span>
            <p class="text-xl sm:text-2xl font-bold text-amber-600 mt-1">{{ $classAverages['total_terlambat'] ?? 0 }}</p>
            <span class="text-[10px] text-slate-400">Presensi terlambat</span>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-semibold text-rose-700 uppercase tracking-wider block">Total Alpa</span>
            <p class="text-xl sm:text-2xl font-bold text-rose-600 mt-1">{{ $classAverages['total_alpa'] }}</p>
            <span class="text-[10px] text-slate-400">Tanpa keterangan</span>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs col-span-2 sm:col-span-1">
            <span class="text-[10px] font-semibold text-slate-700 uppercase tracking-wider block">% Rombel</span>
            <p class="text-xl sm:text-2xl font-bold text-slate-900 mt-1">{{ $classAverages['avg_percentage'] }}%</p>
            <span class="text-[10px] text-slate-400">Rata-rata kehadiran</span>
        </div>
    </div>

    <!-- Tabel Rekap Satu Kelas -->
    <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-4 bg-slate-50/70 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="font-bold text-xs uppercase tracking-wide text-slate-900">
                    Tabel Rekap Kehadiran: {{ $selectedClassroom->name }}
                </h3>
                <span class="text-[11px] text-slate-500">Tahun Ajaran: {{ $selectedClassroom->academicYear->year_name ?? '-' }}</span>
            </div>
            <span class="text-xs text-slate-500 font-medium">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs min-w-[820px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                        <th class="py-2.5 px-3.5 w-10 text-center">No</th>
                        <th class="py-2.5 px-3.5 w-24">NIS</th>
                        <th class="py-2.5 px-3.5">Nama Siswa</th>
                        <th class="py-2.5 px-3.5 text-center text-emerald-700 font-bold">Hadir</th>
                        <th class="py-2.5 px-3.5 text-center text-amber-700 font-bold">Telat</th>
                        <th class="py-2.5 px-3.5 text-center text-yellow-700 font-bold">Sakit</th>
                        <th class="py-2.5 px-3.5 text-center text-sky-700 font-bold">Izin</th>
                        <th class="py-2.5 px-3.5 text-center text-rose-700 font-bold">Alpa</th>
                        <th class="py-2.5 px-3.5 text-center text-slate-600 font-bold">Total</th>
                        <th class="py-2.5 px-3.5 text-center font-bold">% Kehadiran</th>
                        <th class="py-2.5 px-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($recapStudents as $idx => $item)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-2.5 px-3.5 text-center text-slate-400 text-xs">
                            {{ $loop->iteration }}
                        </td>
                        <td class="py-2.5 px-3.5 text-xs text-slate-600 font-medium">
                            {{ $item['student']->nis }}
                        </td>
                        <td class="py-2.5 px-3.5 font-semibold text-slate-900">
                            {{ $item['student']->name }}
                        </td>
                        <td class="py-2.5 px-3.5 text-center font-bold text-emerald-600">
                            {{ $item['hadir'] }}
                        </td>
                        <td class="py-2.5 px-3.5 text-center font-bold text-amber-600">
                            {{ $item['terlambat'] ?? 0 }}
                        </td>
                        <td class="py-2.5 px-3.5 text-center font-bold text-yellow-600">
                            {{ $item['sakit'] }}
                        </td>
                        <td class="py-2.5 px-3.5 text-center font-bold text-sky-600">
                            {{ $item['izin'] }}
                        </td>
                        <td class="py-2.5 px-3.5 text-center font-bold text-rose-600">
                            {{ $item['alpa'] }}
                        </td>
                        <td class="py-2.5 px-3.5 text-center font-semibold text-slate-600">
                            {{ $item['total'] }}
                        </td>
                        <td class="py-2.5 px-3.5 text-center whitespace-nowrap">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold {{ $item['percentage'] >= 85 ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($item['percentage'] >= 75 ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-rose-100 text-rose-800 border border-rose-200') }}">
                                {{ $item['percentage'] }}%
                            </span>
                        </td>
                        <td class="py-2.5 px-3.5 text-right">
                            <a href="{{ route('recap.student', ['student_id' => $item['student']->id, 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                                class="inline-block px-2.5 py-1 text-[11px] font-medium rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-8 text-slate-400 text-xs">
                            Tidak ada siswa yang cocok dengan kriteria pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
