@extends('layouts.app')

@section('title', 'Tahun Ajaran')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-brand-600 transition">Home</a>
                <span>/</span>
                <span class="text-slate-400">Master Data</span>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Tahun Ajaran</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Tahun Ajaran</h1>
            <p class="text-xs text-slate-500 mt-1">Atur periode kalender akademik sekolah dan tetapkan tahun ajaran aktif.</p>
        </div>
        <button type="button" onclick="document.getElementById('modalAddAcademicYear').classList.remove('hidden')"
            class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs md:text-sm shadow-sm shadow-brand-500/20 transition flex items-center space-x-2 self-start sm:self-auto">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>+ Tambah Tahun Ajaran</span>
        </button>
    </div>

    <!-- Panduan Halaman Callout -->
    <div class="p-4 rounded-2xl bg-brand-50/70 border border-brand-100 flex items-start gap-3">
        <div class="w-8 h-8 rounded-xl bg-brand-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-brand-500/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="text-xs text-slate-600 leading-relaxed">
            <span class="font-bold text-slate-900">Panduan Halaman:</span> Tahun ajaran yang bertanda <strong class="text-emerald-700">Aktif</strong> akan menjadi rujukan utama untuk seluruh data absensi, rombel, dan rekapitulasi saat ini. Hanya ada satu tahun ajaran yang aktif dalam satu waktu.
        </div>
    </div>

    <!-- Tabel Tahun Ajaran -->
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4 md:p-5 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">
                Daftar Kalender Akademik ({{ $academicYears->count() }} Periode)
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs md:text-sm min-w-[650px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Tahun Ajaran</th>
                        <th class="py-3 px-4">Tanggal Mulai</th>
                        <th class="py-3 px-4">Tanggal Selesai</th>
                        <th class="py-3 px-4">Jumlah Rombel</th>
                        <th class="py-3 px-4 w-32 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($academicYears as $idx => $ay)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-4 text-center text-xs font-semibold text-slate-400">
                            {{ $loop->iteration }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            {{ $ay->year_name }}
                        </td>
                        <td class="py-3.5 px-4 text-xs text-slate-600">
                            {{ $ay->start_date->isoFormat('D MMMM Y') }}
                        </td>
                        <td class="py-3.5 px-4 text-xs text-slate-600">
                            {{ $ay->end_date->isoFormat('D MMMM Y') }}
                        </td>
                        <td class="py-3.5 px-4 text-xs font-semibold text-slate-700">
                            {{ $ay->classrooms_count }} Rombel
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($ay->is_active)
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Aktif
                            </span>
                            @else
                            <form action="{{ route('academic-years.toggle', $ay) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium bg-slate-100 hover:bg-slate-200 text-slate-600 border border-slate-200 transition">
                                    Set Aktif
                                </button>
                            </form>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                            <button type="button" onclick="editAcademicYear({{ $ay->id }}, '{{ $ay->year_name }}', '{{ $ay->start_date->toDateString() }}', '{{ $ay->end_date->toDateString() }}', {{ $ay->is_active ? 1 : 0 }})"
                                class="inline-block px-3 py-1.5 text-xs font-semibold rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-700 transition">
                                Edit
                            </button>
                            @if(!$ay->is_active)
                            <form action="{{ route('academic-years.destroy', $ay) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun ajaran {{ $ay->year_name }}?')">
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
                        <td colspan="7" class="text-center py-12 text-slate-400 text-sm">
                            Belum ada tahun ajaran yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Tahun Ajaran -->
<div id="modalAddAcademicYear" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="font-bold text-slate-900 text-base">Tambah Tahun Ajaran Baru</h3>
            <button type="button" onclick="document.getElementById('modalAddAcademicYear').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center">
                &times;
            </button>
        </div>
        <form method="POST" action="{{ route('academic-years.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="year_name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Tahun Ajaran *</label>
                <input type="text" id="year_name" name="year_name" required placeholder="Contoh: 2026/2027"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="start_date" class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Mulai *</label>
                    <input type="date" id="start_date" name="start_date" required
                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600">
                </div>
                <div>
                    <label for="end_date" class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Selesai *</label>
                    <input type="date" id="end_date" name="end_date" required
                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600">
                </div>
            </div>
            <div class="flex items-center space-x-2.5 pt-1">
                <input type="checkbox" id="is_active" name="is_active" value="1" class="w-4 h-4 rounded text-brand-600 border-slate-300 focus:ring-brand-500">
                <label for="is_active" class="text-xs font-medium text-slate-700">Jadikan Tahun Ajaran Aktif Saat Ini</label>
            </div>
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modalAddAcademicYear').classList.add('hidden')" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-sm shadow-brand-500/20 transition">
                    Simpan Tahun Ajaran
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Tahun Ajaran -->
<div id="modalEditAcademicYear" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="font-bold text-slate-900 text-base">Perbarui Tahun Ajaran</h3>
            <button type="button" onclick="document.getElementById('modalEditAcademicYear').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center">
                &times;
            </button>
        </div>
        <form id="formEditAcademicYear" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_year_name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Tahun Ajaran *</label>
                <input type="text" id="edit_year_name" name="year_name" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_start_date" class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Mulai *</label>
                    <input type="date" id="edit_start_date" name="start_date" required
                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600">
                </div>
                <div>
                    <label for="edit_end_date" class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Selesai *</label>
                    <input type="date" id="edit_end_date" name="end_date" required
                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600">
                </div>
            </div>
            <div class="flex items-center space-x-2.5 pt-1">
                <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="w-4 h-4 rounded text-brand-600 border-slate-300 focus:ring-brand-500">
                <label for="edit_is_active" class="text-xs font-medium text-slate-700">Jadikan Tahun Ajaran Aktif Saat Ini</label>
            </div>
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modalEditAcademicYear').classList.add('hidden')" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-sm shadow-brand-500/20 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function editAcademicYear(id, yearName, startDate, endDate, isActive) {
        document.getElementById('formEditAcademicYear').action = `/academic-years/${id}`;
        document.getElementById('edit_year_name').value = yearName;
        document.getElementById('edit_start_date').value = startDate;
        document.getElementById('edit_end_date').value = endDate;
        document.getElementById('edit_is_active').checked = isActive === 1;
        document.getElementById('modalEditAcademicYear').classList.remove('hidden');
    }
</script>
@endsection

