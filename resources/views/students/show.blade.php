@extends('layouts.app')

@section('title', 'Detail Siswa: ' . $student->name)

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
                <span class="text-slate-800 font-semibold">Detail Siswa</span>
            </div>
            <div class="flex items-center space-x-3.5">
                <div class="w-12 h-15 rounded-xl border-2 border-slate-200 bg-white shadow-2xs overflow-hidden shrink-0">
                    @if($student->photo && file_exists(public_path($student->photo)))
                        <img src="{{ asset($student->photo) }}" alt="{{ $student->name }}" class="w-full h-full object-cover object-top">
                    @else
                        <div class="w-full h-full bg-gradient-to-b from-sky-900 via-indigo-950 to-slate-900 flex flex-col items-center justify-center text-white">
                            <span class="font-bold text-xs">{{ strtoupper(substr($student->name, 0, 2)) }}</span>
                            <span class="text-[6.5px] text-sky-200 uppercase mt-0.5">{{ $student->gender == 'L' ? 'L' : 'P' }}</span>
                        </div>
                    @endif
                </div>
                <div>
                    <div class="flex items-center space-x-3">
                        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $student->name }}</h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $student->status == 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">
                        NIS: <span class="text-slate-800 font-semibold">{{ $student->nis }}</span> &bull; 
                        NISN: <span class="text-slate-800 font-semibold">{{ $student->nisn ?? '-' }}</span> &bull; 
                        Kelas: <span class="text-brand-600 font-bold">{{ $student->classroom->name }}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2 shrink-0 self-start sm:self-auto">
            @if(auth()->user()?->isAdmin())
            <a href="{{ route('students.edit', $student) }}" class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-sm shadow-brand-500/20 transition flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                <span>Edit Profil</span>
            </a>
            @endif
            <a href="{{ route('students.index') }}" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs shadow-sm transition">
                &larr; Kembali
            </a>
        </div>
    </div>

    <!-- Alert Panggilan Orang Tua jika Sering Terlambat (Repeat Offender) -->
    @if($needsParentCall)
    <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-sm">
        <div class="flex items-start space-x-3">
            <div class="p-2 bg-rose-100 rounded-lg text-rose-600 shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-bold text-rose-900">Perhatian: Siswa Memerlukan Pembinaan Khusus / Panggilan Orang Tua</h4>
                <p class="text-xs text-rose-700 mt-0.5">
                    Siswa telah tercatat terlambat sebanyak <strong class="underline font-bold">{{ $totalTelatBulanIni }} kali</strong> pada bulan ini. Sesuai tata tertib sekolah, batas maksimal toleransi adalah 3 kali sebelum penerbitan Surat Peringatan (SP1) / Pemanggilan Wali Murid ke Ruang BK.
                </p>
            </div>
        </div>
        @if($student->parent_phone)
            @php
                $cleanPhone = preg_replace('/[^0-9]/', '', $student->parent_phone);
                if (str_starts_with($cleanPhone, '0')) {
                    $cleanPhone = '62' . substr($cleanPhone, 1);
                }
                $waMsg = rawurlencode("Assalamu'alaikum Wr. Wb. Yth. Bapak/Ibu Wali dari {$student->name} ({$student->classroom->name}). Kami dari Tim Kesiswaan/BK ingin menginformasikan bahwa ananda telah tercatat terlambat hadir sebanyak {$totalTelatBulanIni} kali bulan ini. Mohon berkenan konfirmasi kehadiran untuk koordinasi pembinaan. Terima kasih.");
            @endphp
            <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waMsg }}" target="_blank" class="inline-flex items-center justify-center space-x-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold shrink-0 shadow-sm transition">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                </svg>
                <span>Hubungi Orang Tua (WA)</span>
            </a>
        @endif
    </div>
    @endif

    <!-- Info Profil & Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Info Biodata Siswa & Kontak Orang Tua -->
        <div class="bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center justify-between">
                <span>Biodata Pokok & Kontak</span>
                <span class="text-xs text-slate-400 font-medium">ID: #{{ $student->id }}</span>
            </h3>
            
            <div class="space-y-3 text-xs">
                <div>
                    <span class="text-slate-400 block mb-0.5">Rombongan Belajar</span>
                    <span class="font-semibold text-slate-800 text-sm">{{ $student->classroom->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5">Tahun Ajaran</span>
                    <span class="font-medium text-slate-700">{{ $student->classroom->academicYear->year_name ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5">Jenis Kelamin</span>
                    <span class="font-medium text-slate-700">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5">Tempat, Tanggal Lahir</span>
                    <span class="font-medium text-slate-700">
                        {{ $student->birth_place ?? '-' }}, {{ $student->birth_date ? $student->birth_date->isoFormat('D MMMM Y') : '-' }}
                    </span>
                </div>
                
                <!-- Info Orang Tua -->
                <div class="pt-2 border-t border-slate-100">
                    <span class="text-slate-400 block mb-0.5">Nama Orang Tua / Wali</span>
                    <span class="font-semibold text-slate-800">{{ $student->parent_name ?? 'Belum diisi' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5">Kontak WhatsApp Orang Tua</span>
                    @if($student->parent_phone)
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-800">{{ $student->parent_phone }}</span>
                            @php
                                $cleanPhone = preg_replace('/[^0-9]/', '', $student->parent_phone);
                                if (str_starts_with($cleanPhone, '0')) {
                                    $cleanPhone = '62' . substr($cleanPhone, 1);
                                }
                            @endphp
                            <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="text-emerald-600 hover:text-emerald-700 font-semibold text-[11px] flex items-center">
                                Chat WA &rarr;
                            </a>
                        </div>
                    @else
                        <span class="text-slate-400 italic">Belum diisi</span>
                    @endif
                </div>

                <div>
                    <span class="text-slate-400 block mb-0.5">Alamat Domisili</span>
                    <span class="text-slate-700 leading-relaxed">{{ $student->address ?? '-' }}</span>
                </div>
            </div>

            <!-- Kartu Pelajar Button -->
            <div class="pt-3 border-t border-slate-100">
                <a href="{{ route('students.cards', ['classroom_id' => $student->classroom_id]) }}" class="w-full inline-flex items-center justify-center space-x-2 py-2 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                    </svg>
                    <span>Cetak Kartu Pelajar Siswa</span>
                </a>
            </div>
        </div>

        <!-- Rekapitulasi Presensi & Disiplin -->
        <div class="md:col-span-2 bg-white p-6 rounded-xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Statistik Absensi & Poin Kedisiplinan</h3>
                    <span class="text-xs text-slate-500">Total: <strong>{{ $totalHari }}</strong> Hari Efektif</span>
                </div>
                
                <!-- Row 1: Kehadiran -->
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 my-4">
                    <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200 text-center">
                        <span class="text-[11px] font-bold text-emerald-800">Tepat Waktu (H)</span>
                        <p class="text-2xl font-black text-emerald-700 mt-0.5">{{ $totalHadir }}</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200 text-center">
                        <span class="text-[11px] font-bold text-amber-800">Terlambat (T)</span>
                        <p class="text-2xl font-black text-amber-700 mt-0.5">{{ $totalTerlambat }}</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-blue-50/70 border border-blue-200 text-center">
                        <span class="text-[11px] font-bold text-blue-800">Sakit (S)</span>
                        <p class="text-2xl font-black text-blue-700 mt-0.5">{{ $totalSakit }}</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-purple-50/70 border border-purple-200 text-center">
                        <span class="text-[11px] font-bold text-purple-800">Izin (I)</span>
                        <p class="text-2xl font-black text-purple-700 mt-0.5">{{ $totalIzin }}</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-rose-50/70 border border-rose-200 text-center col-span-2 sm:col-span-1">
                        <span class="text-[11px] font-bold text-rose-800">Alpa (A)</span>
                        <p class="text-2xl font-black text-rose-700 mt-0.5">{{ $totalAlpa }}</p>
                    </div>
                </div>

                <!-- Discipline Point Bar & Attendance Rate -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-slate-500">Persentase Kehadiran</span>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $totalHadir + $totalTerlambat }} dari {{ $totalHari }} pertemuan</p>
                        </div>
                        <span class="text-3xl font-black {{ $persentase >= 85 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $persentase }}%
                        </span>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-slate-500">Total Poin Pelanggaran BK</span>
                            <p class="text-xs text-slate-400 mt-0.5">Bulan ini: <strong class="text-slate-700">{{ $totalTelatBulanIni }}x terlambat</strong></p>
                        </div>
                        <div class="text-right">
                            <span class="text-3xl font-black {{ $totalPoin > 20 ? 'text-rose-600' : ($totalPoin > 0 ? 'text-amber-600' : 'text-emerald-600') }}">
                                {{ $totalPoin }}
                            </span>
                            <span class="text-[10px] font-bold block uppercase text-slate-400">Poin</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 mt-5 flex items-center justify-between">
                <a href="{{ route('recap.student', ['student_id' => $student->id]) }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-brand-600 hover:text-brand-700">
                    <span>Lihat Rekapitulasi Kalender Lengkap</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>

                <a href="{{ route('public.check', ['nis' => $student->nis]) }}" target="_blank" class="inline-flex items-center space-x-1 text-xs font-medium text-slate-500 hover:text-slate-700">
                    <span>Lihat Tampilan Wali Murid</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Timeline Riwayat Presensi & Kedisiplinan -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm p-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-900">Timeline Presensi Harian & Pemantauan Disiplin</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar rekaman kehadiran harian beserta deteksi jam pelajaran terlambat dan pemenuhan tugas kompensasi.</p>
            </div>
            <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-3 py-1 rounded-full">
                {{ $attendances->total() }} Rekaman Tercatat
            </span>
        </div>

        @if($attendances->count() > 0)
            <div class="relative pl-6 sm:pl-8 border-l-2 border-slate-200 space-y-6 ml-2">
                @foreach($attendances as $item)
                    @php
                        $sessionDate = $item->attendanceSession ? $item->attendanceSession->attendance_date->translatedFormat('l, d F Y') : '-';
                        $isLate = ($item->status === 'T');
                        $isAlpha = ($item->status === 'A');
                        $isNormal = ($item->status === 'H');
                    @endphp
                    <div class="relative group">
                        <!-- Bullet indicator -->
                        <div class="absolute -left-[31px] sm:-left-[39px] top-2 w-5 h-5 rounded-full border-4 border-white shadow-sm flex items-center justify-center 
                            {{ $isLate ? 'bg-amber-500 ring-2 ring-amber-200' : ($isAlpha ? 'bg-rose-500 ring-2 ring-rose-200' : ($isNormal ? 'bg-emerald-500 ring-2 ring-emerald-200' : 'bg-blue-500 ring-2 ring-blue-200')) }}">
                        </div>

                        <!-- Card Content -->
                        <div class="bg-slate-50/70 hover:bg-slate-50 border border-slate-200/80 rounded-xl p-4 sm:p-5 transition">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-200/60">
                                <div>
                                    <span class="text-xs font-bold text-slate-700 tracking-wide uppercase">{{ $sessionDate }}</span>
                                    <div class="flex flex-wrap items-center gap-x-3 text-xs text-slate-500 mt-0.5">
                                        <span>Tap Masuk: <strong class="text-slate-800">{{ $item->scanned_at ? $item->scanned_at->format('H:i:s') : '-' }}</strong></span>
                                        @if($item->tap_out_at)
                                            <span>• Tap Pulang: <strong class="text-slate-800">{{ $item->tap_out_at->format('H:i:s') }}</strong></span>
                                        @endif
                                        @if($item->recorder)
                                            <span class="text-[11px] text-slate-400">Petugas: {{ $item->recorder->name }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2">
                                    @if($item->status === 'H')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-100 text-emerald-800">
                                            ✓ Tepat Waktu
                                        </span>
                                    @elseif($item->status === 'T')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-amber-100 text-amber-800">
                                            ⚠️ Terlambat +{{ $item->late_minutes ?? 0 }}m
                                        </span>
                                    @elseif($item->status === 'S')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-100 text-blue-800">
                                            Sakit
                                        </span>
                                    @elseif($item->status === 'I')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-purple-100 text-purple-800">
                                            Izin
                                        </span>
                                    @elseif($item->status === 'A')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-rose-100 text-rose-800">
                                            ✕ Alpa
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Detail Keterlambatan, Jam Pelajaran & Kompensasi -->
                            @if($isLate)
                                <div class="mt-3 pt-2 text-xs space-y-3">
                                    @if(!empty($item->missed_periods))
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            <span class="font-bold text-amber-900">Jam Pelajaran:</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-amber-200/80 text-amber-900 font-semibold text-[11px]">
                                                {{ $item->missed_periods }}
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Compensation Task Box with Toggle Action -->
                                    @if($item->compensation_task)
                                        <div class="bg-amber-100/40 border border-amber-200/90 rounded-lg p-3">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                                <div class="flex items-start space-x-2">
                                                    <svg class="w-4 h-4 text-amber-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <div>
                                                        <span class="font-bold text-amber-950">Tugas Kompensasi / Edukatif:</span>
                                                        <p class="text-slate-800 font-medium mt-0.5">{{ $item->compensation_task }}</p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center space-x-2 shrink-0">
                                                    @if($item->compensation_status === 'completed')
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-emerald-100 text-emerald-800">
                                                            ✓ Selesai Dikerjakan
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-rose-100 text-rose-800">
                                                            ⏳ Belum Tuntas
                                                        </span>
                                                    @endif

                                                    <!-- Form Toggle Status Kompensasi -->
                                                    <form action="{{ route('attendance.toggle-compensation', $item->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="px-2.5 py-1 rounded text-xs font-semibold border {{ $item->compensation_status === 'completed' ? 'border-slate-300 text-slate-600 hover:bg-slate-100' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm' }} transition">
                                                            {{ $item->compensation_status === 'completed' ? 'Tandai Belum' : 'Tandai Selesai' }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex items-center space-x-4 text-[11px] text-slate-500">
                                        <span>Poin Kedisiplinan: <strong class="text-rose-600 font-bold">+{{ $item->discipline_points ?? 0 }} Poin</strong></span>
                                        <span>Toleransi Bel: <strong>{{ $item->attendanceSession->schoolSetting->entry_time ?? '07:00' }} WIB</strong></span>
                                    </div>
                                </div>
                            @endif

                            @if($item->notes)
                                <div class="mt-2 text-xs text-slate-600 italic bg-white p-2 rounded border border-slate-200">
                                    Catatan: "{{ $item->notes }}"
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6 pt-4 border-t border-slate-100">
                {{ $attendances->links() }}
            </div>
        @else
            <div class="text-center py-12 text-slate-500">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-sm font-medium">Belum ada rekaman presensi pada siswa ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection
