@extends('layouts.app')

@section('title', 'Tambah Siswa Massal')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-brand-600 transition">Home</a>
                <span>/</span>
                <a href="{{ route('students.index') }}" class="hover:text-brand-600 transition">Data Siswa</a>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Input Massal (Batch)</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Input Siswa Massal / Roster Rombel</h1>
            <p class="text-xs text-slate-500 mt-1">Tambahkan puluhan data siswa sekaligus dengan menyalin (copy-paste) dari Microsoft Excel, Google Sheets, atau Word.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('students.index') }}" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs md:text-sm shadow-sm transition">
                &larr; Kembali ke Data Siswa
            </a>
        </div>
    </div>

    <!-- Panduan Format Callout -->
    <div class="p-5 rounded-xl bg-brand-50/70 border border-brand-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start space-x-3.5">
            <div class="w-9 h-9 rounded-2xl bg-brand-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-brand-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-xs md:text-sm text-slate-900">Format Baris per Baris:</h4>
                <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">
                    Setiap baris memuat: <code class="bg-white px-2 py-0.5 rounded border border-brand-200 text-brand-700 font-bold">NIS, Nama Siswa, L/P, NISN (Opsional)</code>
                </p>
                <p class="text-[11px] text-slate-500 mt-1">
                    *Pemisah kolom dapat berupa tanda koma (<code>,</code>), titik koma (<code>;</code>), atau <strong>Tab langsung dari Excel</strong> (cukup blok kolom di Excel lalu Paste).
                </p>
            </div>
        </div>
        <button type="button" onclick="fillExample()" class="px-3.5 py-2 rounded-xl bg-white hover:bg-brand-50 text-brand-600 font-bold text-xs border border-brand-200 shadow-sm transition shrink-0">
            Isi Contoh Otomatis
        </button>
    </div>

    <!-- Form Input Massal -->
    <div class="bg-white p-6 sm:p-8 rounded-xl border border-slate-100 shadow-sm">
        <form method="POST" action="{{ route('students.batch-store') }}" class="space-y-6">
            @csrf

            <!-- Pilih Kelas Tujuan -->
            <div class="max-w-md">
                <label for="classroom_id" class="block text-xs font-bold text-slate-700 mb-2">
                    Pilih Rombongan Belajar (Kelas) Tujuan *
                </label>
                <select id="classroom_id" name="classroom_id" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 bg-white">
                    <option value="">-- Pilih Rombel --</option>
                    @foreach($classrooms as $c)
                    <option value="{{ $c->id }}" {{ old('classroom_id') == $c->id ? 'selected' : '' }}>
                        {{ $c->name }} ({{ $c->academicYear->year_name ?? '-' }})
                    </option>
                    @endforeach
                </select>
                @error('classroom_id')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Area Teks Data Siswa -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="raw_data" class="block text-xs font-bold text-slate-700">
                        Tempelkan (Paste) Daftar Siswa di Sini *
                    </label>
                    <span id="lineCount" class="text-xs font-semibold text-slate-400">0 baris terdeteksi</span>
                </div>
                <textarea id="raw_data" name="raw_data" rows="12" required
                    oninput="updateCount()"
                    placeholder="Contoh:&#10;1001, Ahmad Fauzi, L, 0081234567&#10;1002, Siti Nurhaliza, P, 0087654321&#10;1003, Budi Santoso, L, 0089988776&#10;1004, Dewi Lestari, P, 0085544332"
                    class="w-full text-xs md:text-sm px-4 py-3 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 leading-relaxed">{{ old('raw_data') }}</textarea>
                @error('raw_data')
                    <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('students.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs md:text-sm shadow-md shadow-brand-500/20 transition flex items-center space-x-2">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan & Import Seluruh Siswa</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateCount() {
        const text = document.getElementById('raw_data').value.trim();
        if (!text) {
            document.getElementById('lineCount').textContent = '0 baris terdeteksi';
            return;
        }
        const lines = text.split('\n').filter(l => l.trim().length > 0);
        document.getElementById('lineCount').textContent = lines.length + ' baris terdeteksi';
    }

    function fillExample() {
        const example = `2026001, Adinda Putri Maharani, P, 008123001
2026002, Bagas Pratama, L, 008123002
2026003, Cantika Dewi, P, 008123003
2026004, Dimas Arya Wijaya, L, 008123004
2026005, Eka Rahmadani, P, 008123005
2026006, Farhan Maulana, L, 008123006
2026007, Gita Permatasari, P, 008123007
2026008, Hafizh Al Ghifari, L, 008123008`;
        document.getElementById('raw_data').value = example;
        updateCount();
    }
</script>
@endsection
