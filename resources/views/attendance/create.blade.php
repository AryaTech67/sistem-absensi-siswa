@extends('layouts.app')

@section('title', 'Pencatatan Absensi Siswa')

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
                <span class="text-slate-800 font-semibold">Input Absensi Baru</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Formulir Input Absensi Siswa</h1>
            <p class="text-xs text-slate-500 mt-1">Pilih rombel dan tanggal, lalu tetapkan status kehadiran siswa secara massal.</p>
        </div>
        <a href="{{ route('attendance.index') }}" class="inline-flex items-center space-x-1.5 px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs shadow-sm transition self-start sm:self-auto">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Riwayat Sesi</span>
        </a>
    </div>

    <!-- Panduan Halaman Callout (OnHadir Style) -->
    <div class="p-4 rounded-2xl bg-brand-50/70 border border-brand-100 flex items-start gap-3">
        <div class="w-8 h-8 rounded-xl bg-brand-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-brand-500/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="text-xs text-slate-600 leading-relaxed">
            <span class="font-bold text-slate-900">Panduan Halaman:</span> Pilih Rombongan Belajar (Rombel), tanggal, dan sesi absensi di bawah ini. Tekan tombol <strong class="text-emerald-700">"Tandai Semua Hadir"</strong> untuk mempercepat pengisian kehadiran satu kelas, lalu sesuaikan siswa yang berhalangan (Sakit, Izin, atau Alpa).
        </div>
    </div>

    <!-- Filter Pilihan Kelas & Tanggal (Form GET) -->
    <div class="bg-white p-5 md:p-6 rounded-xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('attendance.create') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label for="classroom_id" class="block text-xs font-bold text-slate-700 mb-1.5">Rombongan Belajar (Rombel)</label>
                <select id="classroom_id" name="classroom_id" onchange="this.form.submit()"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs md:text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition bg-white">
                    <option value="">-- Pilih Rombel --</option>
                    @foreach($classrooms as $cls)
                    <option value="{{ $cls->id }}" {{ $selectedClassroomId == $cls->id ? 'selected' : '' }}>
                        {{ $cls->name }} ({{ $cls->academicYear->year_name ?? '-' }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="date" class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Absensi</label>
                <input type="date" id="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs md:text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition">
            </div>

            <div>
                <label for="session_name" class="block text-xs font-bold text-slate-700 mb-1.5">Sesi Absensi</label>
                <select id="session_name" name="session_name" onchange="this.form.submit()"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs md:text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 transition bg-white">
                    <option value="Pagi" {{ $sessionName == 'Pagi' ? 'selected' : '' }}>Pagi</option>
                    <option value="Siang" {{ $sessionName == 'Siang' ? 'selected' : '' }}>Siang</option>
                </select>
            </div>

            <div>
                <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs md:text-sm font-semibold transition shadow-sm shadow-brand-500/20 flex items-center justify-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Muat Data Murid</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Peringatan jika sesi sudah pernah dicatat -->
    @if($existingSession)
    <div class="p-5 rounded-xl bg-amber-50/90 border border-amber-200 text-amber-900 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-start space-x-3.5">
            <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h3 class="font-bold text-sm text-amber-900">Sesi Absensi Sudah Pernah Dicatat</h3>
                <p class="text-xs text-amber-700 mt-0.5">
                    Data kehadiran untuk rombel ini pada <strong>{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}</strong> (Sesi {{ $sessionName }}) telah tersimpan di database.
                </p>
            </div>
        </div>
        <div class="flex items-center space-x-2 shrink-0 w-full sm:w-auto">
            <a href="{{ route('attendance.show', $existingSession) }}" class="flex-1 sm:flex-none text-center px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs transition shadow-sm">
                Lihat Detail
            </a>
            <a href="{{ route('attendance.edit', $existingSession) }}" class="flex-1 sm:flex-none text-center px-4 py-2 rounded-xl bg-white border border-amber-300 text-amber-800 hover:bg-amber-50 font-semibold text-xs transition">
                Perbaiki Absensi
            </a>
        </div>
    </div>
    @endif

    <!-- Form Input Massal Absensi -->
    @if($selectedClassroomId && $students->isNotEmpty() && !$existingSession)
    <form method="POST" action="{{ route('attendance.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="classroom_id" value="{{ $selectedClassroomId }}">
        <input type="hidden" name="attendance_date" value="{{ $selectedDate }}">
        <input type="hidden" name="session_name" value="{{ $sessionName }}">

        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Action Toolbar Atas Tabel (Tandai Semua Hadir) -->
            <div class="p-4 md:p-5 bg-slate-50/70 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center space-x-2.5">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">
                            Daftar Murid Rombel ({{ $students->count() }} Orang)
                        </h3>
                        <p class="text-[11px] text-slate-400">Kelas {{ $selectedClassroom->name ?? '' }} &bull; Tanggal {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d M Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-2 self-start sm:self-auto">
                    <!-- Tombol Tandai Semua Hadir -->
                    <button type="button" onclick="markAllPresent()"
                        class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm shadow-emerald-500/20 transition flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Tandai Semua Hadir (H)</span>
                    </button>
                </div>
            </div>

            <!-- Hint Mobile Scroll -->
            <div class="md:hidden px-4 py-2 bg-slate-100/70 text-[11px] text-slate-500 flex items-center space-x-1.5 border-b border-slate-100">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                <span>Geser ke samping untuk melihat seluruh kolom</span>
            </div>

            <!-- Tabel Siswa (Mobile Responsive Scroll) -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs md:text-sm min-w-[700px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4 w-28">NIS</th>
                            <th class="py-3 px-4">Nama Siswa</th>
                            <th class="py-3 px-4 w-20 text-center">L/P</th>
                            <th class="py-3 px-4 w-80 text-center">Status Kehadiran</th>
                            <th class="py-3 px-4 w-60">Catatan / Alasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($students as $idx => $student)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-400 text-xs">
                                {{ $idx + 1 }}
                            </td>
                            <td class="py-3.5 px-4 text-xs font-semibold text-slate-700">
                                {{ $student->nis }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $student->name }}
                                <input type="hidden" name="attendances[{{ $idx }}][student_id]" value="{{ $student->id }}">
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $student->gender == 'L' ? 'bg-blue-50 text-brand-700' : 'bg-pink-50 text-pink-700' }}">
                                    {{ $student->gender }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex rounded-xl p-1 bg-slate-100/80 border border-slate-200 text-xs font-semibold">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendances[{{ $idx }}][status]" value="H" class="sr-only peer status-radio" checked>
                                        <span class="px-2.5 py-1 rounded-lg block peer-checked:bg-emerald-600 peer-checked:text-white text-slate-600 hover:text-slate-900 transition">
                                            H (Hadir)
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendances[{{ $idx }}][status]" value="S" class="sr-only peer status-radio">
                                        <span class="px-2.5 py-1 rounded-lg block peer-checked:bg-amber-500 peer-checked:text-white text-slate-600 hover:text-slate-900 transition">
                                            S (Sakit)
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendances[{{ $idx }}][status]" value="I" class="sr-only peer status-radio">
                                        <span class="px-2.5 py-1 rounded-lg block peer-checked:bg-brand-600 peer-checked:text-white text-slate-600 hover:text-slate-900 transition">
                                            I (Izin)
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendances[{{ $idx }}][status]" value="A" class="sr-only peer status-radio">
                                        <span class="px-2.5 py-1 rounded-lg block peer-checked:bg-rose-600 peer-checked:text-white text-slate-600 hover:text-slate-900 transition">
                                            A (Alpa)
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <input type="text" name="attendances[{{ $idx }}][notes]"
                                    placeholder="Keterangan opsional..."
                                    class="w-full px-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 placeholder:text-slate-400">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tombol Submit Bawah Form -->
            <div class="p-4 md:p-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-500 text-center sm:text-left">
                    Pastikan status kehadiran telah sesuai dengan daftar fisik siswa sebelum menyimpan.
                </p>
                <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs md:text-sm shadow-md shadow-brand-500/20 transition flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Data Presensi</span>
                </button>
            </div>
        </div>
    </form>
    @elseif($selectedClassroomId && $students->isEmpty())
    <div class="p-12 text-center bg-white rounded-xl border border-slate-100 shadow-sm">
        <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3 border border-amber-200">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-800">Kelas {{ $selectedClassroom->name ?? 'Ini' }}: Belum Ada Data Murid Terdaftar</h3>
        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
            Daftar nama murid untuk kelas ini belum dimasukkan. Anda dapat mendaftarkan murid kapan saja melalui menu <strong>Data Siswa</strong>.
        </p>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('students.create') }}" class="mt-4 inline-flex items-center space-x-1.5 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-sm transition">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>+ Tambah Murid ke Kelas {{ $selectedClassroom->name ?? '' }}</span>
        </a>
        @endif
    </div>
    @elseif(!$selectedClassroomId)
    <div class="p-14 text-center bg-white rounded-xl border border-slate-100 shadow-sm">
        <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mx-auto mb-4 border border-brand-100">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
        </div>
        <h3 class="text-base font-bold text-slate-800">Pilih Rombongan Belajar (Rombel) Terlebih Dahulu</h3>
        <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto leading-relaxed">
            Silakan pilih salah satu rombel di formulir atas untuk memuat daftar nama murid kelas tersebut dan mencatat kehadirannya secara massal.
        </p>
    </div>
    @endif
</div>

@push('scripts')
<script>
    function markAllPresent() {
        const radios = document.querySelectorAll('input.status-radio[value="H"]');
        radios.forEach(radio => {
            radio.checked = true;
        });
    }
</script>
@endpush
@endsection

