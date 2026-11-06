@extends('layouts.app')

@section('title', 'Pengaturan Sekolah & Kop Surat')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-brand-600 transition">Home</a>
                <span>/</span>
                <span class="text-slate-400">Sistem</span>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Pengaturan Sekolah</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pengaturan Identitas, Jam Sekolah & Cadangan</h1>
            <p class="text-xs text-slate-500 mt-1">Kustomisasi identitas sekolah, jam bel masuk & toleransi kedisiplinan, serta pencadangan database sistem.</p>
        </div>

        <!-- 1-Click Database Backup Button -->
        <div class="shrink-0">
            <a href="{{ route('settings.backup') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Unduh Backup Database (.sqlite)</span>
            </a>
        </div>
    </div>

    <!-- Alert Sukses -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center space-x-2">
        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Form Pengaturan Sekolah & Jam Bel Masuk (7 Kolom) -->
        <div class="lg:col-span-7 bg-white p-6 sm:p-7 rounded-xl border border-slate-200/80 shadow-sm space-y-6">
            <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-brand-600 inline-block"></span>
                    <span>Pengaturan Jadwal Bel & Toleransi Masuk</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Digunakan scanner otomatis untuk menghitung menit keterlambatan & jam pelajaran tertinggal.</p>
            </div>

            <form method="POST" action="{{ route('settings.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Jam Bel Masuk, Pulang, dan Toleransi -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label for="entry_time" class="block text-xs font-bold text-slate-700 mb-1">
                                Jam Bel Masuk <span class="text-rose-500">*</span>
                            </label>
                            <input type="time" id="entry_time" name="entry_time" value="{{ old('entry_time', $setting->entry_time ?? '07:00') }}" required
                                class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 bg-white">
                            <span class="text-[10px] text-slate-400 mt-1 block">Waktu bel sekolah dimulai</span>
                        </div>

                        <div>
                            <label for="departure_time" class="block text-xs font-bold text-slate-700 mb-1">
                                Jam Bel Pulang <span class="text-rose-500">*</span>
                            </label>
                            <input type="time" id="departure_time" name="departure_time" value="{{ old('departure_time', $setting->departure_time ?? '15:30') }}" required
                                class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 bg-white">
                            <span class="text-[10px] text-slate-400 mt-1 block">Waktu sesi presensi pulang</span>
                        </div>

                        <div>
                            <label for="tolerance_minutes" class="block text-xs font-bold text-slate-700 mb-1">
                                Toleransi (Menit) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" id="tolerance_minutes" name="tolerance_minutes" min="0" max="60" value="{{ old('tolerance_minutes', $setting->tolerance_minutes ?? 15) }}" required
                                class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs font-semibold focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 bg-white">
                            <span class="text-[10px] text-slate-400 mt-1 block">Lewat menit ini &rarr; Terlambat</span>
                        </div>
                    </div>
                </div>

                <!-- Form Identitas Resmi Sekolah -->
                <div class="pt-2 space-y-4">
                    <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Identitas Resmi Sekolah</h3>

                    <div>
                        <label for="agency_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Dinas / Yayasan Pengampu <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="agency_name" name="agency_name" value="{{ old('agency_name', $setting->agency_name) }}" required
                            oninput="document.getElementById('preview_agency').innerText = this.value || 'DINAS PENDIDIKAN & KEBUDAYAAN'"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs md:text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition"
                            placeholder="Contoh: DINAS PENDIDIKAN PROVINSI JAWA BARAT">
                    </div>

                    <div>
                        <label for="school_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Lengkap Sekolah <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="school_name" name="school_name" value="{{ old('school_name', $setting->school_name) }}" required
                            oninput="document.getElementById('preview_school').innerText = this.value || 'SMK NEGERI CONTOH'"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs md:text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition font-bold"
                            placeholder="Contoh: SMK NEGERI 1 KOTA BANDUNG">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="school_address" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Alamat Jalan & No. <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="school_address" name="school_address" value="{{ old('school_address', $setting->school_address) }}" required
                                oninput="updateAddressPreview()"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs md:text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition"
                                placeholder="Jl. Raya Utama No. 123">
                        </div>

                        <div>
                            <label for="city" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Kota / Kabupaten <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="city" name="city" value="{{ old('city', $setting->city) }}" required
                                oninput="document.getElementById('preview_city').innerText = this.value || 'Kota'"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs md:text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition"
                                placeholder="Contoh: Kota Bandung">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="school_phone" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Telepon</label>
                            <input type="text" id="school_phone" name="school_phone" value="{{ old('school_phone', $setting->school_phone) }}"
                                oninput="updateAddressPreview()"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs md:text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition"
                                placeholder="(022) 1234567">
                        </div>

                        <div>
                            <label for="school_website" class="block text-xs font-bold text-slate-700 mb-1.5">Website Sekolah</label>
                            <input type="text" id="school_website" name="school_website" value="{{ old('school_website', $setting->school_website) }}"
                                oninput="updateAddressPreview()"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs md:text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition"
                                placeholder="www.sekolah.sch.id">
                        </div>
                    </div>
                </div>

                <!-- Pejabat Penandatangan Dokumen -->
                <div class="pt-3 border-t border-slate-100">
                    <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-3">Pejabat Penandatangan Dokumen</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="principal_name" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nama Kepala Sekolah <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="principal_name" name="principal_name" value="{{ old('principal_name', $setting->principal_name) }}" required
                                oninput="document.getElementById('preview_principal').innerText = this.value || 'Nama Kepala Sekolah'"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs md:text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition font-medium"
                                placeholder="Drs. H. Mulyadi, M.Pd.">
                        </div>

                        <div>
                            <label for="principal_nip" class="block text-xs font-bold text-slate-700 mb-1.5">NIP Kepala Sekolah</label>
                            <input type="text" id="principal_nip" name="principal_nip" value="{{ old('principal_nip', $setting->principal_nip) }}"
                                oninput="document.getElementById('preview_nip').innerText = 'NIP. ' + (this.value || '-')"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs md:text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition font-medium"
                                placeholder="19720315 199802 1 002">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs md:text-sm shadow-md shadow-brand-500/20 transition duration-150 flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Simpan Pengaturan Sistem</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Live Preview KOP Surat Resmi & Info Database (5 Kolom) -->
        <div class="lg:col-span-5 space-y-4">
            
            <!-- Live Preview KOP Surat -->
            <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm">
                <div class="flex items-center space-x-2 pb-3 mb-4 border-b border-slate-100">
                    <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-900">Live Preview KOP Surat</h3>
                        <p class="text-[10px] text-slate-400">Otomatis tercetak pada format laporan resmi PDF & Excel</p>
                    </div>
                </div>

                <!-- Kertas Simulasi KOP -->
                <div class="p-4 bg-slate-50/60 rounded-xl border border-slate-200">
                    <div class="border-b-2 border-slate-900 pb-3 mb-3 text-center">
                        <h4 id="preview_agency" class="text-[10px] font-bold tracking-wider uppercase text-slate-600">
                            {{ $setting->agency_name }}
                        </h4>
                        <h3 id="preview_school" class="text-xs font-black tracking-tight text-slate-900 uppercase mt-0.5">
                            {{ $setting->school_name }}
                        </h3>
                        <p id="preview_contact" class="text-[9px] text-slate-500 mt-1 leading-tight">
                            {{ $setting->school_address }} &bull; Telp. {{ $setting->school_phone }} &bull; Website: {{ $setting->school_website }}
                        </p>
                    </div>

                    <div class="py-3 text-center text-[11px] text-slate-400 border border-dashed border-slate-300 rounded-lg mb-3 bg-white">
                        [ Konten Lembar Rekapitulasi Presensi ]
                    </div>

                    <!-- Tanda Tangan -->
                    <div class="text-center text-[11px] pt-1">
                        <p class="text-[10px] font-medium text-slate-600"><span id="preview_city">{{ $setting->city }}</span>, {{ date('d F Y') }}</p>
                        <p class="text-[10px] font-semibold text-slate-800 mt-0.5">Mengetahui, Kepala Sekolah</p>
                        <div class="h-10"></div>
                        <p id="preview_principal" class="font-bold underline text-slate-900 uppercase text-[11px]">{{ $setting->principal_name }}</p>
                        <p id="preview_nip" class="text-slate-500 text-[10px] font-medium">NIP. {{ $setting->principal_nip ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Database Security & Backup Card -->
            <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-3">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900">Pencadangan Basis Data Mandiri</h4>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                            Simpan salinan database lokal Anda secara rutin. File berformat <code>.sqlite</code> ini dapat dipulihkan kapan saja saat terjadi kendala perangkat atau migrasi server sekolah.
                        </p>
                    </div>
                </div>
                <div class="pt-2">
                    <a href="{{ route('settings.backup') }}" class="w-full inline-flex items-center justify-center space-x-2 py-2 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Download Cadangan Sekarang</span>
                    </a>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
    function updateAddressPreview() {
        const addr = document.getElementById('school_address').value || 'Alamat Sekolah';
        const phone = document.getElementById('school_phone').value || '-';
        const web = document.getElementById('school_website').value || '-';
        document.getElementById('preview_contact').innerText = addr + ' • Telp. ' + phone + ' • Website: ' + web;
    }
</script>
@endsection
