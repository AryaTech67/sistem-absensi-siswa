<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassroomController extends Controller
{
    /**
     * Tampilkan daftar rombongan belajar.
     */
    public function index(): View
    {
        $classrooms = Classroom::with(['academicYear', 'waliKelas'])
            ->withCount('students')
            ->orderBy('name')
            ->get();

        $academicYears = AcademicYear::orderByDesc('is_active')->orderBy('year_name')->get();
        $teachers = User::where('status', 'aktif')->orderBy('name')->get();

        return view('classrooms.index', compact('classrooms', 'academicYears', 'teachers'));
    }

    /**
     * Simpan rombongan belajar baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'wali_kelas_id' => ['nullable', 'exists:users,id'],
        ], [
            'name.required' => 'Nama rombel (kelas) wajib diisi.',
            'academic_year_id.required' => 'Tahun ajaran wajib dipilih.',
            'academic_year_id.exists' => 'Tahun ajaran yang dipilih tidak valid.',
        ]);

        Classroom::create($validated);

        return redirect()->route('classrooms.index')
            ->with('success', "Rombel {$validated['name']} berhasil ditambahkan.");
    }

    /**
     * Tampilkan anggota kelas dalam rombel tertentu (FR-16).
     */
    public function show(Classroom $classroom): View
    {
        $classroom->load(['academicYear', 'waliKelas', 'students' => fn($q) => $q->orderBy('name')]);

        return view('classrooms.show', compact('classroom'));
    }

    /**
     * Perbarui data rombongan belajar.
     */
    public function update(Request $request, Classroom $classroom): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'wali_kelas_id' => ['nullable', 'exists:users,id'],
        ], [
            'name.required' => 'Nama rombel (kelas) wajib diisi.',
            'academic_year_id.required' => 'Tahun ajaran wajib dipilih.',
        ]);

        $classroom->update($validated);

        return redirect()->route('classrooms.index')
            ->with('success', "Rombel {$classroom->name} berhasil diperbarui.");
    }

    /**
     * Hapus rombel dengan pencegahan jika merusak relasi/riwayat (FR-15).
     */
    public function destroy(Classroom $classroom): RedirectResponse
    {
        // Proteksi jika masih ada siswa
        if ($classroom->students()->exists()) {
            return back()->with('error', "Rombel {$classroom->name} tidak dapat dihapus karena masih memiliki data siswa terdaftar.");
        }

        // Proteksi jika masih ada sesi absensi
        if ($classroom->attendanceSessions()->exists()) {
            return back()->with('error', "Rombel {$classroom->name} tidak dapat dihapus karena memiliki riwayat sesi absensi.");
        }

        $name = $classroom->name;
        $classroom->delete();

        return redirect()->route('classrooms.index')
            ->with('success', "Rombel {$name} berhasil dihapus.");
    }
}
