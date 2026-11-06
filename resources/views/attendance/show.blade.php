@extends('layouts.app')

@section('title', 'Detail Sesi Absensi')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-brand-600 transition">Home</a>
                <span>/</span>
                <a href="{{ route('attendance.index') }}" class="hover:text-brand-600 transition">Absensi</a>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Detail Sesi</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                Absensi {{ $attendanceSession->classroom->name }} - {{ $attendanceSession->attendance_date->isoFormat('dddd, D MMMM Y') }}
            </h1>
            <p class="text-xs text-slate-500 mt-1">Sesi: <span class="font-semibold text-slate-700">{{ $attendanceSession->session_name }}</span> &bull; Dicatat oleh: <span class="font-semibold text-slate-700">{{ $attendanceSession->creator->name ?? '-' }}</span> ({{ $attendanceSession->created_at->format('d/m/Y H:i') }} WIB)</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 shrink-0 self-start sm:self-auto">
            <a href="{{ route('attendance.print', ['attendance' => $attendanceSession->id]) }}" target="_blank"
                class="px-3.5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs md:text-sm shadow-sm shadow-brand-500/20 transition flex items-center space-x-1.5">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak PDF</span>
            </a>
            <a href="{{ route('attendance.export-excel', ['attendance' => $attendanceSession->id]) }}"
                class="px-3.5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs md:text-sm shadow-sm shadow-emerald-500/20 transition flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Ekspor Excel (.xls)</span>
            </a>
            <a href="{{ route('attendance.edit', ['attendance' => $attendanceSession->id]) }}" class="px-3.5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs md:text-sm shadow-sm transition flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                <span>Edit Absensi</span>
            </a>
            <a href="{{ route('attendance.index') }}" class="px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs md:text-sm shadow-sm transition">
                &larr; Kembali
            </a>
        </div>
    </div>

    <!-- Ringkasan Kartu 4 Status -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-emerald-600 block">Hadir (H)</span>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">
                    {{ $attendanceSession->attendances->where('status', 'H')->count() }}
                </p>
                <span class="text-[11px] text-slate-400">Siswa hadir</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                H
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-amber-600 block">Sakit (S)</span>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">
                    {{ $attendanceSession->attendances->where('status', 'S')->count() }}
                </p>
                <span class="text-[11px] text-slate-400">Surat keterangan</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                S
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-brand-600 block">Izin (I)</span>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">
                    {{ $attendanceSession->attendances->where('status', 'I')->count() }}
                </p>
                <span class="text-[11px] text-slate-400">Izin resmi</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-sm">
                I
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-rose-600 block">Alpa (A)</span>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">
                    {{ $attendanceSession->attendances->where('status', 'A')->count() }}
                </p>
                <span class="text-[11px] text-slate-400">Tanpa keterangan</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-sm">
                A
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Kehadiran Siswa -->
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4 md:p-5 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">
                Daftar Presensi Siswa ({{ $attendanceSession->attendances->count() }} Orang)
            </h3>
            <span class="text-xs text-slate-400">Selesai dicatat</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs md:text-sm min-w-[620px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4 w-28">NIS</th>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4 w-32 text-center">Status</th>
                        <th class="py-3 px-4">Keterangan / Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($attendanceSession->attendances as $idx => $att)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-4 text-center font-semibold text-slate-400 text-xs">
                            {{ $idx + 1 }}
                        </td>
                        <td class="py-3.5 px-4 text-xs font-semibold text-slate-700">
                            {{ $att->student->nis ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            {{ $att->student->name ?? 'Siswa Terhapus' }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($att->status == 'H')
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Hadir
                            </span>
                            @elseif($att->status == 'S')
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                Sakit
                            </span>
                            @elseif($att->status == 'I')
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-brand-700 border border-brand-200">
                                Izin
                            </span>
                            @elseif($att->status == 'A')
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                Alpa
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-xs text-slate-600">
                            {{ $att->notes ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Riwayat Audit Perubahan Status (BR-12 & ERD) -->
    @php
        $allAudits = $attendanceSession->attendances->flatMap->audits;
    @endphp
    @if($allAudits->isNotEmpty())
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5 md:p-6">
        <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center space-x-2">
            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Log Audit Perubahan Data Absensi</span>
        </h3>
        <div class="space-y-2 text-xs">
            @foreach($allAudits->sortByDesc('changed_at') as $audit)
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <span class="font-bold text-slate-800">{{ $audit->attendance->student->name ?? 'Siswa' }}</span>
                    <span class="text-slate-500">diubah dari status </span>
                    <span class="font-bold text-slate-700">{{ $audit->old_status }}</span>
                    <span class="text-slate-500">menjadi </span>
                    <span class="font-bold text-brand-700">{{ $audit->new_status }}</span>
                </div>
                <div class="text-[11px] text-slate-400">
                    <span>Oleh: <strong>{{ $audit->user->name ?? 'Sistem' }}</strong></span> &bull; 
                    <span>{{ $audit->changed_at->format('d/m/Y H:i') }} WIB</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

