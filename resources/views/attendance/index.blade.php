@extends('layouts.app')

@section('title', 'Riwayat Sesi Absensi')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-brand-600 transition">Home</a>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Riwayat Absensi</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Riwayat Sesi Absensi</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar seluruh sesi pencatatan presensi yang telah tersimpan di sistem.</p>
        </div>
        <a href="{{ route('attendance.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs md:text-sm shadow-sm shadow-brand-500/20 transition self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>+ Catat Absensi Baru</span>
        </a>
    </div>

    <!-- Panduan Halaman Callout -->
    <div class="p-4 rounded-2xl bg-brand-50/70 border border-brand-100 flex items-start gap-3">
        <div class="w-8 h-8 rounded-xl bg-brand-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-brand-500/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="text-xs text-slate-600 leading-relaxed">
            <span class="font-bold text-slate-900">Panduan Halaman:</span> Di sini tersimpan seluruh berkas absensi harian. Anda dapat menyaring data berdasarkan rombel atau tanggal, membuka <strong>Detail</strong> untuk melihat nama siswa dan log audit, atau memilih <strong>Edit</strong> untuk memperbaiki absensi.
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('attendance.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 items-end">
            <div>
                <label for="classroom_id" class="block text-xs font-bold text-slate-700 mb-1.5">Filter Rombel</label>
                <select id="classroom_id" name="classroom_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs md:text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition bg-white">
                    <option value="">Semua Rombel</option>
                    @foreach($classrooms as $cls)
                    <option value="{{ $cls->id }}" {{ request('classroom_id') == $cls->id ? 'selected' : '' }}>
                        {{ $cls->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date" class="block text-xs font-bold text-slate-700 mb-1.5">Filter Tanggal</label>
                <input type="date" id="date" name="date" value="{{ request('date') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs md:text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="flex-1 py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white text-xs md:text-sm font-semibold rounded-xl shadow-sm shadow-brand-500/20 transition">
                    Terapkan Filter
                </button>
                @if(request()->hasAny(['classroom_id', 'date']))
                <a href="{{ route('attendance.index') }}" class="py-2.5 px-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs md:text-sm font-semibold rounded-xl transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Daftar Sesi -->
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4 md:p-5 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">
                Daftar Riwayat Sesi ({{ $sessions->total() }} Sesi Tercatat)
            </h3>
            <span class="text-xs text-slate-400">Terbaru di atas</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs md:text-sm min-w-[760px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-3 px-4">Tanggal Presensi</th>
                        <th class="py-3 px-4">Rombel</th>
                        <th class="py-3 px-4">Sesi</th>
                        <th class="py-3 px-4">Rekap Kehadiran (H / S / I / A)</th>
                        <th class="py-3 px-4">Petugas</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sessions as $session)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <p class="font-bold text-slate-900">{{ $session->attendance_date->isoFormat('dddd, D MMMM Y') }}</p>
                            <p class="text-[11px] text-slate-400">Waktu input: {{ $session->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-800">
                            {{ $session->classroom->name }}
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700">
                                {{ $session->session_name }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="flex items-center space-x-1.5 text-xs font-bold">
                                <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200" title="Hadir: {{ $session->hadir_count }}">
                                    H: {{ $session->hadir_count }}
                                </span>
                                <span class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200" title="Sakit: {{ $session->sakit_count }}">
                                    S: {{ $session->sakit_count }}
                                </span>
                                <span class="px-2 py-0.5 rounded-md bg-blue-50 text-brand-700 border border-brand-200" title="Izin: {{ $session->izin_count }}">
                                    I: {{ $session->izin_count }}
                                </span>
                                <span class="px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200" title="Alpa: {{ $session->alpa_count }}">
                                    A: {{ $session->alpa_count }}
                                </span>
                                <span class="text-slate-400 text-[11px] font-normal ml-1">
                                    ({{ $session->total_students }} Siswa)
                                </span>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 text-xs text-slate-600">
                            {{ $session->creator->name ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                            <a href="{{ route('attendance.show', ['attendance' => $session->id]) }}" class="inline-block px-2.5 py-1.5 text-xs font-semibold rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                Detail
                            </a>
                            <a href="{{ route('attendance.print', ['attendance' => $session->id]) }}" target="_blank" title="Cetak Lembar PDF" class="inline-block px-2.5 py-1.5 text-xs font-semibold rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-700 transition">
                                PDF
                            </a>
                            <a href="{{ route('attendance.export-excel', ['attendance' => $session->id]) }}" title="Unduh File Excel" class="inline-block px-2.5 py-1.5 text-xs font-semibold rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 transition">
                                Excel
                            </a>
                            <a href="{{ route('attendance.edit', ['attendance' => $session->id]) }}" class="inline-block px-2.5 py-1.5 text-xs font-semibold rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-700 transition">
                                Edit
                            </a>
                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('attendance.destroy', ['attendance' => $session->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data sesi absensi ini? Seluruh catatan absensi pada sesi ini akan terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1.5 text-xs font-semibold rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 transition">
                                    Hapus
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-slate-400 text-sm">
                            <p class="font-semibold text-slate-600">Tidak ada data sesi absensi ditemukan</p>
                            <p class="text-xs text-slate-400 mt-1">Coba sesuaikan filter atau tambahkan absensi baru untuk rombel Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sessions->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $sessions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

