@extends('layouts.app')

@section('title', 'Perbaiki Absensi')

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
                <a href="{{ route('attendance.show', $attendanceSession) }}" class="hover:text-brand-600 transition">Detail</a>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Perbaiki</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Perbaiki Catatan Absensi</h1>
            <p class="text-xs text-slate-500 mt-1">
                {{ $attendanceSession->classroom->name }} &bull; Tanggal: {{ $attendanceSession->attendance_date->isoFormat('dddd, D MMMM Y') }} (Sesi: {{ $attendanceSession->session_name }})
            </p>
        </div>
        <a href="{{ route('attendance.show', ['attendance' => $attendanceSession->id]) }}" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-xs md:text-sm shadow-sm transition self-start sm:self-auto">
            &larr; Batalkan
        </a>
    </div>

    <form method="POST" action="{{ route('attendance.update', ['attendance' => $attendanceSession->id]) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Notice Box -->
            <div class="p-4 bg-amber-50/80 border-b border-amber-200/80 text-xs text-amber-900 flex items-center space-x-2.5">
                <div class="w-6 h-6 rounded-lg bg-amber-500 text-white flex items-center justify-center shrink-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span>Setiap modifikasi data kehadiran siswa akan otomatis terekam ke dalam <strong>Log Audit Sistem (BR-12)</strong> dengan timestamp dan identitas akun Anda.</span>
            </div>

            <!-- Hint Mobile Scroll -->
            <div class="md:hidden px-4 py-2 bg-slate-100/70 text-[11px] text-slate-500 flex items-center space-x-1.5 border-b border-slate-100">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                <span>Geser ke samping untuk melihat opsi status</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs md:text-sm min-w-[700px]">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            <th class="py-3 px-4 w-12 text-center">No</th>
                            <th class="py-3 px-4 w-28">NIS</th>
                            <th class="py-3 px-4">Nama Siswa</th>
                            <th class="py-3 px-4 w-80 text-center">Status Kehadiran</th>
                            <th class="py-3 px-4 w-60">Catatan / Alasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($attendanceSession->attendances as $idx => $att)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 text-center text-xs text-slate-400 font-semibold">
                                {{ $idx + 1 }}
                            </td>
                            <td class="py-3.5 px-4 text-xs font-semibold text-slate-700">
                                {{ $att->student->nis ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $att->student->name ?? 'Siswa Terhapus' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex rounded-xl p-1 bg-slate-100/80 border border-slate-200 text-xs font-semibold">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendances[{{ $att->id }}][status]" value="H" class="sr-only peer" {{ $att->status == 'H' ? 'checked' : '' }}>
                                        <span class="px-2.5 py-1 rounded-lg block peer-checked:bg-emerald-600 peer-checked:text-white text-slate-600 hover:text-slate-900 transition">
                                            H (Hadir)
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendances[{{ $att->id }}][status]" value="S" class="sr-only peer" {{ $att->status == 'S' ? 'checked' : '' }}>
                                        <span class="px-2.5 py-1 rounded-lg block peer-checked:bg-amber-500 peer-checked:text-white text-slate-600 hover:text-slate-900 transition">
                                            S (Sakit)
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendances[{{ $att->id }}][status]" value="I" class="sr-only peer" {{ $att->status == 'I' ? 'checked' : '' }}>
                                        <span class="px-2.5 py-1 rounded-lg block peer-checked:bg-brand-600 peer-checked:text-white text-slate-600 hover:text-slate-900 transition">
                                            I (Izin)
                                        </span>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendances[{{ $att->id }}][status]" value="A" class="sr-only peer" {{ $att->status == 'A' ? 'checked' : '' }}>
                                        <span class="px-2.5 py-1 rounded-lg block peer-checked:bg-rose-600 peer-checked:text-white text-slate-600 hover:text-slate-900 transition">
                                            A (Alpa)
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <input type="text" name="attendances[{{ $att->id }}][notes]"
                                    value="{{ old('attendances.'.$att->id.'.notes', $att->notes) }}"
                                    placeholder="Catatan..."
                                    class="w-full px-3 py-1.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 md:p-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                <a href="{{ route('attendance.show', ['attendance' => $attendanceSession->id]) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-900">
                    Batal tanpa menyimpan
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs md:text-sm shadow-md shadow-brand-500/20 transition flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Perubahan Absensi</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

