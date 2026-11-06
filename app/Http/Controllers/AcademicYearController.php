<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    /**
     * Tampilkan daftar tahun ajaran.
     */
    public function index(): View
    {
        $academicYears = AcademicYear::withCount('classrooms')
            ->orderByDesc('is_active')
            ->orderByDesc('year_name')
            ->get();

        return view('academic_years.index', compact('academicYears'));
    }

    /**
     * Simpan tahun ajaran baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'year_name' => ['required', 'string', 'max:50'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'year_name.required' => 'Nama tahun ajaran wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.required' => 'Tanggal akhir wajib diisi.',
            'end_date.after' => 'Tanggal akhir harus lebih besar dari tanggal mulai.',
        ]);

        $isActive = $request->boolean('is_active');

        // Jika diset aktif, nonaktifkan tahun ajaran lain
        if ($isActive) {
            AcademicYear::query()->update(['is_active' => false]);
        }

        AcademicYear::create([
            'year_name' => $validated['year_name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => $isActive,
        ]);

        return redirect()->route('academic-years.index')
            ->with('success', "Tahun ajaran {$validated['year_name']} berhasil ditambahkan.");
    }

    /**
     * Perbarui tahun ajaran.
     */
    public function update(Request $request, AcademicYear $academicYear): RedirectResponse
    {
        $validated = $request->validate([
            'year_name' => ['required', 'string', 'max:50'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'year_name.required' => 'Nama tahun ajaran wajib diisi.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'end_date.required' => 'Tanggal akhir wajib diisi.',
            'end_date.after' => 'Tanggal akhir harus lebih besar dari tanggal mulai.',
        ]);

        $isActive = $request->boolean('is_active');

        if ($isActive) {
            AcademicYear::where('id', '!=', $academicYear->id)->update(['is_active' => false]);
        }

        $academicYear->update([
            'year_name' => $validated['year_name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => $isActive,
        ]);

        return redirect()->route('academic-years.index')
            ->with('success', "Tahun ajaran {$academicYear->year_name} berhasil diperbarui.");
    }

    /**
     * Aktifkan tahun ajaran tertentu.
     */
    public function toggleActive(AcademicYear $academicYear): RedirectResponse
    {
        AcademicYear::query()->update(['is_active' => false]);
        $academicYear->update(['is_active' => true]);

        return redirect()->route('academic-years.index')
            ->with('success', "Tahun ajaran {$academicYear->year_name} sekarang berstatus AKTIF.");
    }

    /**
     * Hapus tahun ajaran jika tidak ada rombel terkait.
     */
    public function destroy(AcademicYear $academicYear): RedirectResponse
    {
        if ($academicYear->classrooms()->exists()) {
            return back()->with('error', "Tahun ajaran {$academicYear->year_name} tidak dapat dihapus karena masih digunakan oleh data rombel.");
        }

        $name = $academicYear->year_name;
        $academicYear->delete();

        return redirect()->route('academic-years.index')
            ->with('success', "Tahun ajaran {$name} berhasil dihapus.");
    }
}
