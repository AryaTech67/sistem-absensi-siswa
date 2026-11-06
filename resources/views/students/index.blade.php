@extends('layouts.app')

@section('title', 'Data Siswa')

@section('breadcrumbs')
    <a href="{{ route('dashboard') }}" class="hover:text-brand-600">Dashboard</a>
    <span class="text-slate-400">/</span>
    <span class="font-semibold text-slate-800">Siswa</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-brand-600">DATA SEKOLAH</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-0.5">
                Manajemen Data Siswa
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Kelola identitas, penempatan rombongan belajar, dan status aktif siswa.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2 self-start sm:self-auto">
            <a href="{{ route('students.cards') }}" class="px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs shadow-sm transition flex items-center space-x-1.5">
                <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
                <span>Cetak Kartu QR</span>
            </a>
            @if(auth()->user()?->isAdmin())
            <a href="{{ route('students.batch-create') }}" class="px-3.5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-sm transition flex items-center space-x-1.5">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                <span>Input Massal</span>
            </a>
            <a href="{{ route('students.create') }}" class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-500/20 transition flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Siswa Baru</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Filter & Pencarian Sesuai FR-10 & FR-11 -->
    <div class="bg-white p-5 rounded-xl border border-slate-200/90 shadow-sm">
        <form method="GET" action="{{ route('students.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
            <div>
                <label for="search" class="block text-xs font-bold text-slate-700 mb-1.5">Pencarian</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Cari NIS atau Nama siswa..."
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500/25 focus:border-brand-600 transition placeholder:text-slate-400">
            </div>

            <div>
                <label for="classroom_id" class="block text-xs font-bold text-slate-700 mb-1.5">Filter Kelas</label>
                <select id="classroom_id" name="classroom_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500/25 focus:border-brand-600 bg-white">
                    <option value="">Semua Rombel</option>
                    @foreach($classrooms as $cls)
                    <option value="{{ $cls->id }}" {{ request('classroom_id') == $cls->id ? 'selected' : '' }}>
                        {{ $cls->name }} ({{ $cls->academicYear->year_name ?? '-' }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-xs font-bold text-slate-700 mb-1.5">Status Siswa</label>
                <select id="status" name="status" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500/25 focus:border-brand-600 bg-white">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tidak aktif" {{ request('status') == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="flex-1 py-2.5 px-4 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl transition shadow-sm">
                    Cari & Filter
                </button>
                @if(request()->hasAny(['search', 'classroom_id', 'status']))
                <a href="{{ route('students.index') }}" class="py-2.5 px-3.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel Siswa -->
    <div class="bg-white rounded-xl border border-slate-200/90 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-5 w-12 text-center">No</th>
                        <th class="py-3 px-5 w-28">NIS</th>
                        <th class="py-3 px-5">Nama Lengkap</th>
                        <th class="py-3 px-5 w-20 text-center">L/P</th>
                        <th class="py-3 px-5">Rombel</th>
                        <th class="py-3 px-5 w-28 text-center">Status</th>
                        <th class="py-3 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($students as $idx => $st)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-5 text-center text-xs font-medium text-slate-400">
                            {{ $students->firstItem() + $idx }}
                        </td>
                        <td class="py-3.5 px-5 text-xs text-slate-700 font-semibold">
                            {{ $st->nis }}
                        </td>
                        <td class="py-3.5 px-5 font-bold text-slate-900">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-7 h-8.5 rounded border border-slate-200 overflow-hidden shrink-0 bg-slate-100 flex items-center justify-center">
                                    @if($st->photo && file_exists(public_path($st->photo)))
                                        <img src="{{ asset($st->photo) }}" alt="{{ $st->name }}" class="w-full h-full object-cover object-top">
                                    @else
                                        <span class="text-[9px] font-bold text-slate-500">{{ strtoupper(substr($st->name, 0, 2)) }}</span>
                                    @endif
                                </div>
                                <span>{{ $st->name }}</span>
                            </div>
                        </td>
                        <td class="py-3.5 px-5 text-center text-xs">
                            <span class="px-2 py-0.5 rounded-lg font-bold {{ $st->gender == 'L' ? 'bg-blue-50 text-brand-600' : 'bg-pink-50 text-pink-600' }}">
                                {{ $st->gender }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-xs font-medium text-slate-700">
                            {{ $st->classroom->name ?? '-' }}
                        </td>
                        <td class="py-3.5 px-5 text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $st->status == 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                {{ ucfirst($st->status) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-right space-x-1.5 whitespace-nowrap">
                            <a href="{{ route('students.show', $st) }}" class="inline-block px-3 py-1.5 text-xs font-semibold rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                Detail
                            </a>
                            @if(auth()->user()?->isAdmin())
                            <a href="{{ route('students.edit', $st) }}" class="inline-block px-3 py-1.5 text-xs font-semibold rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-700 transition">
                                Edit
                            </a>
                            <form action="{{ route('students.destroy', $st) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data siswa {{ $st->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 transition">
                                    Hapus
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-slate-400 text-xs">
                            Tidak ditemukan data siswa yang cocok dengan filter yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $students->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
