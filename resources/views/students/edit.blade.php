@extends('layouts.app')

@section('title', 'Edit Data Siswa')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-brand-600 transition">Home</a>
                <span>/</span>
                <a href="{{ route('students.index') }}" class="hover:text-brand-600 transition">Data Siswa</a>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Perbarui</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Perbarui Profil Murid</h1>
            <p class="text-xs text-slate-500 mt-1">Memperbarui informasi data siswa: <strong class="text-slate-700">{{ $student->name }}</strong></p>
        </div>
        <a href="{{ route('students.index') }}" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs md:text-sm shadow-sm transition self-start sm:self-auto">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white p-6 md:p-8 rounded-xl border border-slate-100 shadow-sm">
        <form method="POST" action="{{ route('students.update', $student) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Upload / Ganti Pas Foto Siswa (3x4) -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <label class="block text-xs font-bold text-slate-800 mb-0.5">Pas Foto Siswa (Ditampilkan di Kartu Pelajar)</label>
                <p class="text-xs text-slate-500 mb-3">Format JPG, PNG, atau WEBP berseragam sekolah resmi. Rekomendasi rasio 3x4 (maksimal 3MB).</p>
                <div class="flex items-center space-x-4">
                    <div id="photoPreviewBox" class="w-16 h-20 rounded-lg border-2 border-slate-300 bg-white flex flex-col items-center justify-center overflow-hidden shrink-0 shadow-2xs relative">
                        @if($student->photo && file_exists(public_path($student->photo)))
                            <img id="photoPreviewImg" src="{{ asset($student->photo) }}" alt="{{ $student->name }}" class="w-full h-full object-cover object-top">
                        @else
                            <svg id="previewPlaceholderIcon" class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span id="previewPlaceholderText" class="text-[9px] text-slate-400 font-semibold mt-1">Foto 3x4</span>
                            <img id="photoPreviewImg" src="" alt="Preview Foto" class="w-full h-full object-cover object-top hidden">
                        @endif
                    </div>
                    <div class="flex-1">
                        <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewPhoto(event)"
                            class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 transition cursor-pointer">
                        <span class="block text-[11px] text-slate-400 mt-1">Pilih file baru jika ingin mengganti pas foto yang sudah ada.</span>
                        @error('photo')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="nis" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Induk Siswa (NIS) *</label>
                    <input type="text" id="nis" name="nis" value="{{ old('nis', $student->nis) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition">
                </div>

                <div>
                    <label for="nisn" class="block text-xs font-bold text-slate-700 mb-1.5">NISN</label>
                    <input type="text" id="nisn" name="nisn" value="{{ old('nisn', $student->nisn) }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition">
                </div>
            </div>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap Siswa *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $student->name) }}" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="gender" class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Kelamin *</label>
                    <select id="gender" name="gender" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition bg-white">
                        <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                        <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                    </select>
                </div>

                <div>
                    <label for="classroom_id" class="block text-xs font-bold text-slate-700 mb-1.5">Rombongan Belajar *</label>
                    <select id="classroom_id" name="classroom_id" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition bg-white">
                        @foreach($classrooms as $cls)
                        <option value="{{ $cls->id }}" {{ old('classroom_id', $student->classroom_id) == $cls->id ? 'selected' : '' }}>
                            {{ $cls->name }} ({{ $cls->academicYear->year_name ?? '-' }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 mb-1.5">Status Keaktifan *</label>
                    <select id="status" name="status" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition bg-white">
                        <option value="aktif" {{ old('status', $student->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="tidak aktif" {{ old('status', $student->status) == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="birth_place" class="block text-xs font-bold text-slate-700 mb-1.5">Tempat Lahir</label>
                    <input type="text" id="birth_place" name="birth_place" value="{{ old('birth_place', $student->birth_place) }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition">
                </div>

                <div>
                    <label for="birth_date" class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Lahir</label>
                    <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $student->birth_date?->toDateString()) }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition">
                </div>
            </div>

            <div>
                <label for="address" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Tempat Tinggal</label>
                <textarea id="address" name="address" rows="2"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition">{{ old('address', $student->address) }}</textarea>
            </div>

            <div class="pt-5 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('students.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs md:text-sm shadow-md shadow-brand-500/20 transition flex items-center space-x-2">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function previewPhoto(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let img = document.getElementById('photoPreviewImg');
                const icon = document.getElementById('previewPlaceholderIcon');
                const text = document.getElementById('previewPlaceholderText');
                if (img) {
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                }
                if (icon) icon.classList.add('hidden');
                if (text) text.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection

