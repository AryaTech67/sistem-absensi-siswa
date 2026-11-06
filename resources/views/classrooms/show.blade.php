@extends('layouts.app')

@section('title', 'Anggota Kelas ' . $classroom->name)

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-brand-600 transition">Home</a>
                <span>/</span>
                <a href="{{ route('classrooms.index') }}" class="hover:text-brand-600 transition">Rombel</a>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Anggota Kelas</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Daftar Murid: {{ $classroom->name }}</h1>
            <p class="text-xs text-slate-500 mt-1">
                Tahun Ajaran: <span class="font-semibold text-slate-700">{{ $classroom->academicYear->year_name ?? '-' }}</span> &bull; 
                Wali Kelas: <span class="font-semibold text-brand-700">{{ $classroom->waliKelas->name ?? 'Belum Ditentukan' }}</span> &bull; 
                Total: <span class="font-semibold text-slate-700">{{ $classroom->students->count() }} Siswa</span>
            </p>
        </div>
        <div class="flex items-center space-x-2 shrink-0 self-start sm:self-auto">
            <a href="{{ route('recap.class', ['classroom_id' => $classroom->id]) }}" class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs md:text-sm shadow-sm shadow-brand-500/20 transition">
                Rekap Presensi Rombel
            </a>
            <a href="{{ route('classrooms.index') }}" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs md:text-sm shadow-sm transition">
                &larr; Kembali
            </a>
        </div>
    </div>

    <!-- Tabel Anggota Rombel -->
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4 md:p-5 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">
                Murid Terdaftar di Kelas {{ $classroom->name }}
            </h3>
            <span class="text-xs text-slate-400">{{ $classroom->students->count() }} Siswa</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs md:text-sm min-w-[620px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4 w-28">NIS</th>
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4 w-20 text-center">L/P</th>
                        <th class="py-3 px-4 w-28 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($classroom->students as $idx => $st)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-4 text-center text-xs font-semibold text-slate-400">
                            {{ $loop->iteration }}
                        </td>
                        <td class="py-3.5 px-4 text-xs font-semibold text-slate-700">
                            {{ $st->nis }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            {{ $st->name }}
                        </td>
                        <td class="py-3.5 px-4 text-center text-xs">
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $st->gender == 'L' ? 'bg-blue-50 text-brand-700' : 'bg-pink-50 text-pink-700' }}">
                                {{ $st->gender }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $st->status == 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                {{ ucfirst($st->status) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                            <a href="{{ route('students.show', $st) }}" class="inline-block px-3 py-1.5 text-xs font-semibold rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                Detail
                            </a>
                            <a href="{{ route('recap.student', ['student_id' => $st->id]) }}" class="inline-block px-3 py-1.5 text-xs font-semibold rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-700 transition">
                                Rekap Siswa
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-slate-400 text-sm">
                            Belum ada siswa yang terdaftar dalam rombel ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

