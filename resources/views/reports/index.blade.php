@extends('layouts.app')

@section('title', 'Laporan Absensi Siswa')

@section('breadcrumbs')
    <a href="{{ route('dashboard') }}" class="hover:text-slate-800 transition">Dashboard</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-500">Laporan</span>
    <span class="text-slate-400">/</span>
    <span class="font-medium text-slate-800">Filter & Cetak</span>
@endsection

@section('content')
<div class="space-y-5">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Laporan Kehadiran Siswa</h1>
            <p class="text-xs text-slate-500 mt-1">Filter data berdasarkan rentang tanggal, rombel, atau siswa untuk dicetak atau diekspor.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 shrink-0 self-start sm:self-auto">
            <a href="{{ route('reports.print', request()->all()) }}" target="_blank"
                class="px-3.5 py-2 rounded-lg bg-slate-900 hover:bg-slate-800 text-white font-medium text-xs shadow-xs transition flex items-center space-x-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak PDF</span>
            </a>
            <a href="{{ route('reports.export-excel', request()->all()) }}"
                class="px-3.5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-xs shadow-xs transition flex items-center space-x-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Ekspor Excel (.xls)</span>
            </a>
            <a href="{{ route('reports.export', request()->all()) }}"
                class="px-3 py-2 rounded-lg bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium text-xs shadow-xs transition flex items-center space-x-1">
                <span>CSV</span>
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-4 sm:p-5 rounded-xl border border-slate-200/90 shadow-xs">
        <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 items-end">
            <div>
                <label for="start_date" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Awal</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate }}" required
                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
            </div>

            <div>
                <label for="end_date" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Akhir</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate }}" required
                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition">
            </div>

            <div>
                <label for="classroom_id" class="block text-xs font-semibold text-slate-700 mb-1">Filter Kelas / Rombel</label>
                <select id="classroom_id" name="classroom_id"
                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-800 transition bg-white">
                    <option value="">Semua Rombel</option>
                    @foreach($classrooms as $cls)
                    <option value="{{ $cls->id }}" {{ $classroomId == $cls->id ? 'selected' : '' }}>
                        {{ $cls->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="flex-1 py-2 px-3 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg shadow-xs transition">
                    Terapkan Laporan
                </button>
                <a href="{{ route('reports.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg border border-slate-200 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Ringkasan Periode Laporan (7 Kolom) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider block">Total Siswa</span>
            <p class="text-xl font-bold text-slate-900 mt-1">{{ count($recapList) }}</p>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-semibold text-emerald-700 uppercase tracking-wider block">Total Hadir</span>
            <p class="text-xl font-bold text-emerald-600 mt-1">{{ $summary['hadir'] }}</p>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-semibold text-amber-700 uppercase tracking-wider block">Terlambat</span>
            <p class="text-xl font-bold text-amber-600 mt-1">{{ $summary['terlambat'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-semibold text-yellow-700 uppercase tracking-wider block">Total Sakit</span>
            <p class="text-xl font-bold text-yellow-600 mt-1">{{ $summary['sakit'] }}</p>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-semibold text-sky-700 uppercase tracking-wider block">Total Izin</span>
            <p class="text-xl font-bold text-sky-600 mt-1">{{ $summary['izin'] }}</p>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs">
            <span class="text-[10px] font-semibold text-rose-700 uppercase tracking-wider block">Total Alpa</span>
            <p class="text-xl font-bold text-rose-600 mt-1">{{ $summary['alpa'] }}</p>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/90 shadow-xs col-span-2 sm:col-span-1">
            <span class="text-[10px] font-semibold text-slate-700 uppercase tracking-wider block">% Presensi</span>
            <p class="text-xl font-bold text-slate-900 mt-1">{{ $summary['overall_percentage'] }}%</p>
        </div>
    </div>

    <!-- Tabel Hasil Laporan -->
    <div class="bg-white rounded-xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-3.5 bg-slate-50/70 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs text-slate-600 font-medium">
            <span>Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }}</strong> - <strong>{{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</strong></span>
            <span>Total: {{ count($recapList) }} Siswa</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs min-w-[820px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                        <th class="py-2.5 px-3.5 w-10 text-center">No</th>
                        <th class="py-2.5 px-3.5 w-24">NIS</th>
                        <th class="py-2.5 px-3.5">Nama Siswa</th>
                        <th class="py-2.5 px-3.5 w-24">Kelas</th>
                        <th class="py-2.5 px-3.5 text-center text-emerald-700 font-bold">Hadir</th>
                        <th class="py-2.5 px-3.5 text-center text-amber-700 font-bold">Telat</th>
                        <th class="py-2.5 px-3.5 text-center text-yellow-700 font-bold">Sakit</th>
                        <th class="py-2.5 px-3.5 text-center text-sky-700 font-bold">Izin</th>
                        <th class="py-2.5 px-3.5 text-center text-rose-700 font-bold">Alpa</th>
                        <th class="py-2.5 px-3.5 text-center text-slate-600 font-bold">Total</th>
                        <th class="py-2.5 px-3.5 text-center font-bold">% Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($recapList as $idx => $row)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-2.5 px-3.5 text-center text-slate-400 text-xs">
                            {{ $loop->iteration }}
                        </td>
                        <td class="py-2.5 px-3.5 text-xs text-slate-600 font-medium">
                            {{ $row['student']->nis }}
                        </td>
                        <td class="py-2.5 px-3.5 font-semibold text-slate-900">
                            {{ $row['student']->name }}
                        </td>
                        <td class="py-2.5 px-3.5 text-xs text-slate-700">
                            {{ $row['student']->classroom->name ?? '-' }}
                        </td>
                        <td class="py-2.5 px-3.5 text-center font-bold text-emerald-600">
                            {{ $row['hadir'] }}
                        </td>
                        <td class="py-2.5 px-3.5 text-center font-bold text-amber-600">
                            {{ $row['terlambat'] ?? 0 }}
                        </td>
                        <td class="py-2.5 px-3.5 text-center font-bold text-yellow-600">
                            {{ $row['sakit'] }}
                        </td>
                        <td class="py-2.5 px-3.5 text-center font-bold text-sky-600">
                            {{ $row['izin'] }}
                        </td>
                        <td class="py-2.5 px-3.5 text-center font-bold text-rose-600">
                            {{ $row['alpa'] }}
                        </td>
                        <td class="py-2.5 px-3.5 text-center font-semibold text-slate-600">
                            {{ $row['total'] }}
                        </td>
                        <td class="py-2.5 px-3.5 text-center whitespace-nowrap">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold {{ $row['percentage'] >= 85 ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($row['percentage'] >= 75 ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-rose-100 text-rose-800 border border-rose-200') }}">
                                {{ $row['percentage'] }}%
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-8 text-slate-400 text-xs">
                            Tidak ada data absensi yang ditemukan pada rentang tanggal tersebut.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
