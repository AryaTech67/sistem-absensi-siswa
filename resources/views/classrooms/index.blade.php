@extends('layouts.app')

@section('title', 'Kelas & Kenaikan Kelas')

@section('breadcrumbs')
    <a href="{{ route('dashboard') }}" class="hover:text-brand-600">Dashboard</a>
    <span class="text-slate-400">/</span>
    <span class="font-semibold text-slate-800">Kelas</span>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-brand-600">DATA SEKOLAH</span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-0.5">
                Rombongan Belajar (Rombel)
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Kelola data kelas yang terikat pada tahun ajaran aktif dan pengaturan siswa.
            </p>
        </div>
        @if(auth()->user()->isAdmin())
        <button type="button" onclick="document.getElementById('modalAddClassroom').classList.remove('hidden')"
            class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-md shadow-brand-500/20 transition flex items-center space-x-2 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>+ Buat Kelas Baru</span>
        </button>
        @endif
    </div>

    <!-- Panduan Halaman Callout -->
    <div class="bg-brand-50/50 border border-brand-200/70 rounded-2xl p-4 flex items-center justify-between text-xs">
        <div class="flex items-center space-x-3">
            <div class="w-7 h-7 rounded-lg bg-brand-100 text-brand-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div>
                <span class="font-bold text-slate-800">Panduan Halaman:</span>
                <span class="text-slate-600 ml-1">Pindahkan siswa ke tingkat berikutnya dengan fitur kenaikan kelas atau kelola daftar rombel per tahun ajaran.</span>
            </div>
        </div>
    </div>

    <!-- Card Kenaikan Kelas (Sesuai Screenshot Gambar 5) -->
    <div class="bg-white rounded-xl border border-slate-200/90 p-6 shadow-sm">
        <div class="border-b border-slate-100 pb-4 mb-4">
            <h2 class="text-lg font-bold text-slate-900">Kenaikan Kelas</h2>
            <p class="text-xs text-slate-500 mt-0.5">Pindahkan siswa dari kelas asal ke kelas tujuan secara massal atau individual.</p>
        </div>

        <div>
            <p class="text-xs font-bold text-slate-700 mb-3">Pilih Kelas</p>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="flex-1">
                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Kelas Asal</label>
                    <select id="asalClass" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 bg-white">
                        <option value="">Pilih kelas asal...</option>
                        @foreach($classrooms as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->students_count }} Siswa)</option>
                        @endforeach
                    </select>
                </div>

                <div class="hidden sm:flex items-center justify-center pt-5 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </div>

                <div class="flex-1">
                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Kelas Tujuan (untuk yang naik)</label>
                    <select id="tujuanClass" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 bg-white">
                        <option value="">Pilih kelas tujuan...</option>
                        @foreach($classrooms as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:pt-5">
                    <button type="button" onclick="alert('Fitur kenaikan kelas otomatis siap dijalankan saat tahun ajaran baru dimulai!')" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs transition shadow-sm">
                        Proses Kenaikan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Rombel Cards -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-slate-900">Daftar Rombongan Belajar Aktif</h3>
            <span class="text-xs text-slate-500">{{ count($classrooms) }} Kelas Terdaftar</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @forelse($classrooms as $cls)
            <div class="bg-white rounded-xl border border-slate-200/90 shadow-sm p-6 flex flex-col justify-between hover:border-brand-300 transition">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="w-11 h-11 rounded-2xl bg-brand-50 text-brand-700 font-extrabold flex items-center justify-center text-sm border border-brand-100 shadow-sm">
                            {{ substr($cls->name, 0, 2) }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                            {{ $cls->academicYear->year_name ?? '-' }}
                        </span>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900">{{ $cls->name }}</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        <span class="font-extrabold text-slate-900 text-sm">{{ $cls->students_count }}</span> Siswa terdaftar
                    </p>
                    <div class="mt-2.5 flex items-center space-x-2 text-xs text-slate-600 bg-slate-50 px-2.5 py-1.5 rounded-xl border border-slate-100">
                        <svg class="w-3.5 h-3.5 text-brand-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="truncate text-[11px]">Wali: <strong class="text-slate-800">{{ $cls->waliKelas->name ?? 'Belum Ditentukan' }}</strong></span>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-5 flex items-center justify-between">
                    <a href="{{ route('classrooms.show', $cls) }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 inline-flex items-center">
                        <span>Lihat Siswa</span>
                        <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    @if(auth()->user()->isAdmin())
                    <div class="flex items-center space-x-1">
                        <button type="button" onclick="editClassroom({{ $cls->id }}, '{{ addslashes($cls->name) }}', {{ $cls->academic_year_id }}, '{{ $cls->wali_kelas_id ?? '' }}')"
                            class="p-2 text-slate-400 hover:text-brand-600 rounded-xl hover:bg-slate-50 transition" title="Edit Rombel">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>
                        <form action="{{ route('classrooms.destroy', $cls) }}" method="POST" onsubmit="return confirm('Hapus rombel {{ $cls->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 rounded-xl hover:bg-slate-50 transition" title="Hapus Rombel">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-3 p-12 text-center bg-white rounded-xl border border-slate-200">
                <p class="text-slate-500 text-sm">Belum ada data rombongan belajar.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Tambah Rombel -->
<div id="modalAddClassroom" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="font-bold text-slate-900 text-base">Buat Kelas Baru</h3>
            <button type="button" onclick="document.getElementById('modalAddClassroom').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                &times;
            </button>
        </div>
        <form method="POST" action="{{ route('classrooms.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Kelas / Rombel *</label>
                <input type="text" id="name" name="name" required placeholder="Contoh: VII A, X RPL 1"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/25 focus:border-brand-600">
            </div>
            <div>
                <label for="academic_year_id" class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Ajaran *</label>
                <select id="academic_year_id" name="academic_year_id" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/25 focus:border-brand-600 bg-white">
                    <option value="">-- Pilih Tahun Ajaran --</option>
                    @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}" {{ $ay->is_active ? 'selected' : '' }}>
                        {{ $ay->year_name }} {{ $ay->is_active ? '(Aktif)' : '' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="wali_kelas_id" class="block text-xs font-bold text-slate-700 mb-1.5">Wali Kelas (Opsional)</label>
                <select id="wali_kelas_id" name="wali_kelas_id"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/25 focus:border-brand-600 bg-white">
                    <option value="">-- Pilih Wali Kelas / Guru --</option>
                    @foreach($teachers as $t)
                    <option value="{{ $t->id }}">
                        {{ $t->name }} {{ $t->nip ? "({$t->nip})" : '' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modalAddClassroom').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-500/20">
                    Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Rombel -->
<div id="modalEditClassroom" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="font-bold text-slate-900 text-base">Perbarui Kelas</h3>
            <button type="button" onclick="document.getElementById('modalEditClassroom').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                &times;
            </button>
        </div>
        <form id="formEditClassroom" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Kelas / Rombel *</label>
                <input type="text" id="edit_name" name="name" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/25 focus:border-brand-600">
            </div>
            <div>
                <label for="edit_academic_year_id" class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Ajaran *</label>
                <select id="edit_academic_year_id" name="academic_year_id" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/25 focus:border-brand-600 bg-white">
                    @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}">{{ $ay->year_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="edit_wali_kelas_id" class="block text-xs font-bold text-slate-700 mb-1.5">Wali Kelas (Opsional)</label>
                <select id="edit_wali_kelas_id" name="wali_kelas_id"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/25 focus:border-brand-600 bg-white">
                    <option value="">-- Pilih Wali Kelas / Guru --</option>
                    @foreach($teachers as $t)
                    <option value="{{ $t->id }}">
                        {{ $t->name }} {{ $t->nip ? "({$t->nip})" : '' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modalEditClassroom').classList.add('hidden')" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-500/20">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function editClassroom(id, name, academicYearId, waliKelasId) {
        document.getElementById('formEditClassroom').action = `/classrooms/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_academic_year_id').value = academicYearId;
        document.getElementById('edit_wali_kelas_id').value = waliKelasId || '';
        document.getElementById('modalEditClassroom').classList.remove('hidden');
    }
</script>
@endsection
