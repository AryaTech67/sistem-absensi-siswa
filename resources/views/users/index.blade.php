@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs font-medium text-slate-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-brand-600 transition">Home</a>
                <span>/</span>
                <span class="text-slate-400">Pengaturan</span>
                <span>/</span>
                <span class="text-slate-800 font-semibold">Pengguna</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Kelola Akun Administrator & Petugas</h1>
            <p class="text-xs text-slate-500 mt-1">Atur hak akses pengguna ke sistem presensi (Administrator & Petugas Absensi).</p>
        </div>
        <button type="button" onclick="document.getElementById('modalAddUser').classList.remove('hidden')"
            class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs md:text-sm shadow-sm shadow-brand-500/20 transition flex items-center space-x-2 self-start sm:self-auto">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>+ Tambah Akun Baru</span>
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
            <span class="font-bold text-slate-900">Panduan Halaman:</span> Role <strong class="text-brand-700">Administrator</strong> memiliki akses penuh ke seluruh fitur dan master data sekolah, sedangkan role <strong class="text-slate-800">Petugas Absensi</strong> difokuskan untuk pencatatan dan perbaikan presensi kelas harian.
        </div>
    </div>

    <!-- Tabel Pengguna -->
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-4 md:p-5 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">
                Daftar Akun Pengguna Terdaftar ({{ $users->count() }} Akun)
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs md:text-sm min-w-[650px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">Alamat Email</th>
                        <th class="py-3 px-4 w-36 text-center">Peran (Role)</th>
                        <th class="py-3 px-4 w-28 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $u)
                    <tr class="hover:bg-slate-50/70 transition">
                        <td class="py-3.5 px-4 text-center text-xs font-semibold text-slate-400">
                            {{ $loop->iteration }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-900">
                            {{ $u->name }}
                            @if($u->nip)
                            <div class="text-[11px] font-normal text-slate-400">NIP. {{ $u->nip }}</div>
                            @endif
                            @if($u->id === auth()->id())
                            <span class="ml-1.5 text-[10px] font-bold text-brand-700 bg-brand-50 px-2 py-0.5 rounded-full border border-brand-100">Akun Anda</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-xs font-medium text-slate-600">
                            {{ $u->email }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($u->isAdmin())
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200">
                                Administrator
                            </span>
                            @else
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                Petugas Absensi
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $u->status == 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                {{ ucfirst($u->status) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right space-x-1 whitespace-nowrap">
                            <button type="button" onclick="editUser({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ $u->email }}', '{{ $u->nip ?? '' }}', '{{ $u->role }}', '{{ $u->status }}')"
                                class="inline-block px-3 py-1.5 text-xs font-semibold rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-700 transition">
                                Edit
                            </button>
                            @if($u->id !== auth()->id())
                            <form action="{{ route('users.destroy', $u) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $u->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1.5 text-xs font-semibold rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 transition">
                                    Hapus
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah User -->
<div id="modalAddUser" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="font-bold text-slate-900 text-base">Tambah Akun Pengguna Baru</h3>
            <button type="button" onclick="document.getElementById('modalAddUser').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center">
                &times;
            </button>
        </div>
        <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap *</label>
                <input type="text" id="name" name="name" required placeholder="Contoh: Siti Rahmawati, S.Pd"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email *</label>
                    <input type="email" id="email" name="email" required placeholder="email@sekolah.sch.id"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600">
                </div>
                <div>
                    <label for="nip" class="block text-xs font-bold text-slate-700 mb-1.5">NIP (Opsional)</label>
                    <input type="text" id="nip" name="nip" placeholder="Contoh: 19850712 201001 1 008"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600">
                </div>
            </div>
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi *</label>
                <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="role" class="block text-xs font-bold text-slate-700 mb-1.5">Peran (Hak Akses) *</label>
                    <select id="role" name="role" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 bg-white">
                        <option value="petugas">Petugas Absensi</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-xs font-bold text-slate-700 mb-1.5">Status Akun *</label>
                    <select id="status" name="status" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 bg-white">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modalAddUser').classList.add('hidden')" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-sm shadow-brand-500/20 transition">
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User -->
<div id="modalEditUser" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="font-bold text-slate-900 text-base">Perbarui Akun Pengguna</h3>
            <button type="button" onclick="document.getElementById('modalEditUser').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center">
                &times;
            </button>
        </div>
        <form id="formEditUser" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="edit_name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap *</label>
                <input type="text" id="edit_name" name="name" required
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email *</label>
                    <input type="email" id="edit_email" name="email" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600">
                </div>
                <div>
                    <label for="edit_nip" class="block text-xs font-bold text-slate-700 mb-1.5">NIP (Opsional)</label>
                    <input type="text" id="edit_nip" name="nip" placeholder="Contoh: 19850712 201001 1 008"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600">
                </div>
            </div>
            <div>
                <label for="edit_password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
                <input type="password" id="edit_password" name="password" placeholder="••••••••"
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_role" class="block text-xs font-bold text-slate-700 mb-1.5">Peran *</label>
                    <select id="edit_role" name="role" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 bg-white">
                        <option value="petugas">Petugas Absensi</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
                <div>
                    <label for="edit_status" class="block text-xs font-bold text-slate-700 mb-1.5">Status *</label>
                    <select id="edit_status" name="status" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-600 bg-white">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-2">
                <button type="button" onclick="document.getElementById('modalEditUser').classList.add('hidden')" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
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
    function editUser(id, name, email, nip, role, status) {
        document.getElementById('formEditUser').action = `/users/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_nip').value = nip || '';
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_role').value = role;
        document.getElementById('edit_status').value = status;
        document.getElementById('modalEditUser').classList.remove('hidden');
    }
</script>
@endsection

