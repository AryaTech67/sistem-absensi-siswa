<?php

namespace App\Http\Controllers;

use App\Models\SchoolSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SchoolSettingController extends Controller
{
    /**
     * Tampilkan formulir pengaturan identitas sekolah, jam bel, & kop surat.
     */
    public function index(): View
    {
        $setting = SchoolSetting::get();
        return view('settings.index', compact('setting'));
    }

    /**
     * Simpan pembaruan identitas sekolah, jadwal jam masuk, & kop surat.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_name' => ['required', 'string', 'max:150'],
            'agency_name' => ['required', 'string', 'max:150'],
            'school_address' => ['required', 'string', 'max:255'],
            'school_phone' => ['nullable', 'string', 'max:50'],
            'school_website' => ['nullable', 'string', 'max:100'],
            'school_email' => ['nullable', 'email', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'principal_name' => ['required', 'string', 'max:150'],
            'principal_nip' => ['nullable', 'string', 'max:50'],
            'entry_time' => ['required', 'string', 'max:10'],
            'departure_time' => ['nullable', 'string', 'max:10'],
            'tolerance_minutes' => ['nullable', 'integer', 'min:0', 'max:60'],
        ], [
            'school_name.required' => 'Nama sekolah wajib diisi.',
            'agency_name.required' => 'Nama dinas / yayasan pengampu wajib diisi.',
            'school_address.required' => 'Alamat sekolah wajib diisi.',
            'city.required' => 'Kota/Kabupaten sekolah wajib diisi.',
            'principal_name.required' => 'Nama kepala sekolah wajib diisi.',
            'entry_time.required' => 'Batas jam masuk wajib diisi.',
        ]);

        $setting = SchoolSetting::get();
        $setting->update($validated);

        return redirect()->route('settings.index')
            ->with('success', 'Identitas sekolah, jadwal bel masuk, dan Kop Surat resmi berhasil diperbarui!');
    }

    /**
     * Unduh berkas cadangan database SQLite sekolah.
     */
    public function downloadBackup(): BinaryFileResponse|RedirectResponse
    {
        $dbPath = database_path('database.sqlite');
        if (file_exists($dbPath)) {
            $filename = 'backup_onhadir_' . date('Y_m_d_His') . '.sqlite';
            return response()->download($dbPath, $filename, [
                'Content-Type' => 'application/x-sqlite3',
            ]);
        }

        return redirect()->back()->with('error', 'Berkas basis data SQLite tidak ditemukan.');
    }
}
