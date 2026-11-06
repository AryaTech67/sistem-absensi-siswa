<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\SchoolSetting;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Tampilkan daftar siswa dengan filter kelas dan pencarian (FR-05).
     */
    public function index(Request $request): View
    {
        $classrooms = Classroom::with('academicYear')->orderBy('name')->get();
        $selectedClassroomId = $request->input('classroom_id');
        $search = $request->input('search');

        $query = Student::with(['classroom.academicYear'])
            ->withCount(['attendances as total_attendances']);

        // Filter berdasarkan rombel
        if ($selectedClassroomId) {
            $query->where('classroom_id', $selectedClassroomId);
        }

        // Filter berdasarkan status siswa (FR-11)
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Pencarian berdasarkan Nama atau NIS
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('students.index', compact('students', 'classrooms', 'selectedClassroomId', 'search'));
    }

    /**
     * Formulir tambah siswa tunggal.
     */
    public function create(): View
    {
        $classrooms = Classroom::with('academicYear')->orderBy('id')->get();
        return view('students.create', compact('classrooms'));
    }

    /**
     * Formulir tambah siswa massal (Batch Create / CSV Paste) - FR-06.
     */
    public function batchCreate(): View
    {
        $classrooms = Classroom::with('academicYear')->orderBy('id')->get();
        return view('students.batch_create', compact('classrooms'));
    }

    /**
     * Simpan data siswa secara massal.
     */
    public function batchStore(Request $request): RedirectResponse
    {
        $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'students_data' => ['required', 'string'],
        ], [
            'classroom_id.required' => 'Rombongan belajar wajib dipilih.',
            'students_data.required' => 'Data teks siswa wajib diisi.',
        ]);

        $classroom = Classroom::findOrFail($request->input('classroom_id'));
        $lines = explode("\n", str_replace("\r", "", $request->input('students_data')));

        $inserted = 0;
        $failed = 0;
        $errors = [];

        foreach ($lines as $index => $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $parts = array_map('trim', explode(',', $line));

            if (count($parts) < 3) {
                $failed++;
                $errors[] = "Baris " . ($index + 1) . ": Format tidak lengkap (harus: NIS, Nama, JK [L/P]).";
                continue;
            }

            $nis = $parts[0];
            $name = $parts[1];
            $gender = strtoupper($parts[2]);

            if (!in_array($gender, ['L', 'P'])) {
                $failed++;
                $errors[] = "Baris " . ($index + 1) . ": Jenis kelamin harus L atau P ({$name}).";
                continue;
            }

            if (Student::where('nis', $nis)->exists()) {
                $failed++;
                $errors[] = "Baris " . ($index + 1) . ": NIS '{$nis}' sudah terdaftar untuk siswa lain.";
                continue;
            }

            Student::create([
                'classroom_id' => $classroom->id,
                'nis' => $nis,
                'name' => $name,
                'gender' => $gender,
                'status' => 'aktif',
            ]);

            $inserted++;
        }

        $message = "Berhasil mengimpor {$inserted} siswa ke kelas {$classroom->name}.";
        if ($failed > 0) {
            $message .= " Gagal {$failed} data. " . implode(' ', array_slice($errors, 0, 3));
        }

        return redirect()->route('students.index', ['classroom_id' => $classroom->id])
            ->with('success', $message);
    }

    /**
     * Halaman cetak kartu pelajar (QR ID Cards) siswa.
     */
    public function cards(Request $request): View
    {
        $classrooms = Classroom::withCount(['students' => fn($q) => $q->where('status', 'aktif')])
            ->with('academicYear')
            ->orderBy('name')
            ->get();
        $selectedClassroomId = $request->input('classroom_id');

        $query = Student::with('classroom.academicYear')->where('status', 'aktif');
        if ($selectedClassroomId) {
            $query->where('classroom_id', $selectedClassroomId);
        }

        $students = $query->orderBy('name')->get();

        return view('students.cards', compact('students', 'classrooms', 'selectedClassroomId'));
    }

    /**
     * Simpan data siswa baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:30', 'unique:students,nis'],
            'nisn' => ['nullable', 'string', 'max:30'],
            'name' => ['required', 'string', 'max:150'],
            'gender' => ['required', Rule::in(['L', 'P'])],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:500'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'parent_name' => ['nullable', 'string', 'max:150'],
            'parent_phone' => ['nullable', 'string', 'max:30'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'status' => ['required', Rule::in(['aktif', 'tidak aktif'])],
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah digunakan oleh siswa lain.',
            'name.required' => 'Nama siswa wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'classroom_id.required' => 'Rombongan belajar wajib dipilih.',
            'classroom_id.exists' => 'Rombongan belajar tidak valid.',
            'photo.image' => 'File foto harus berupa format gambar (JPG, PNG, WEBP).',
            'photo.max' => 'Ukuran file foto maksimal 3MB.',
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'student_' . preg_replace('/[^A-Za-z0-9]/', '', $validated['nis']) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/students'), $filename);
            $validated['photo'] = 'uploads/students/' . $filename;
        }

        Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', "Data siswa {$validated['name']} berhasil ditambahkan.");
    }

    /**
     * Tampilkan detail data siswa dan riwayat timeline kedisiplinannya.
     */
    public function show(Student $student): View
    {
        $student->load(['classroom.academicYear', 'attendances.attendanceSession']);

        $totalHadir = $student->attendances->where('status', 'H')->count();
        $totalTerlambat = $student->attendances->where('status', 'T')->count();
        $totalSakit = $student->attendances->where('status', 'S')->count();
        $totalIzin = $student->attendances->where('status', 'I')->count();
        $totalAlpa = $student->attendances->where('status', 'A')->count();
        $totalHari = $totalHadir + $totalTerlambat + $totalSakit + $totalIzin + $totalAlpa;

        $persentase = $totalHari > 0 ? round((($totalHadir + $totalTerlambat) / $totalHari) * 100, 1) : 0;

        $totalPoin = $student->totalDisciplinePoints();
        $totalTelatBulanIni = $student->lateCountThisMonth();
        $needsParentCall = $student->needsParentCall();

        // Riwayat absensi urut waktu terbaru untuk Timeline Visual
        $attendances = $student->attendances()
            ->with(['attendanceSession', 'recorder'])
            ->orderByDesc('id')
            ->paginate(15);

        return view('students.show', compact(
            'student',
            'totalHadir',
            'totalTerlambat',
            'totalSakit',
            'totalIzin',
            'totalAlpa',
            'totalHari',
            'persentase',
            'totalPoin',
            'totalTelatBulanIni',
            'needsParentCall',
            'attendances'
        ));
    }

    /**
     * Formulir edit data siswa.
     */
    public function edit(Student $student): View
    {
        $classrooms = Classroom::with('academicYear')->orderBy('id')->get();
        return view('students.edit', compact('student', 'classrooms'));
    }

    /**
     * Perbarui data siswa di database.
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'nis' => ['required', 'string', 'max:30', Rule::unique('students', 'nis')->ignore($student->id)],
            'nisn' => ['nullable', 'string', 'max:30'],
            'name' => ['required', 'string', 'max:150'],
            'gender' => ['required', Rule::in(['L', 'P'])],
            'birth_place' => ['nullable', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:500'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'parent_name' => ['nullable', 'string', 'max:150'],
            'parent_phone' => ['nullable', 'string', 'max:30'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'status' => ['required', Rule::in(['aktif', 'tidak aktif'])],
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah digunakan oleh siswa lain.',
            'name.required' => 'Nama siswa wajib diisi.',
            'classroom_id.required' => 'Rombongan belajar wajib dipilih.',
            'photo.image' => 'File foto harus berupa format gambar (JPG, PNG, WEBP).',
            'photo.max' => 'Ukuran file foto maksimal 3MB.',
        ]);

        if ($request->hasFile('photo')) {
            if ($student->photo && file_exists(public_path($student->photo))) {
                @unlink(public_path($student->photo));
            }
            $file = $request->file('photo');
            $filename = 'student_' . preg_replace('/[^A-Za-z0-9]/', '', $validated['nis']) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/students'), $filename);
            $validated['photo'] = 'uploads/students/' . $filename;
        }

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', "Data siswa {$student->name} berhasil diperbarui.");
    }

    /**
     * Hapus data siswa dengan proteksi integritas data riwayat absensi (BR-06).
     */
    public function destroy(Student $student): RedirectResponse
    {
        if ($student->attendances()->exists()) {
            return back()->with('error', "Siswa {$student->name} tidak dapat dihapus karena sudah memiliki riwayat absensi. Silakan ubah statusnya menjadi 'Tidak Aktif' untuk menjaga integritas data.");
        }

        if ($student->photo && file_exists(public_path($student->photo))) {
            @unlink(public_path($student->photo));
        }

        $student->delete();

        return redirect()->route('students.index')
            ->with('success', "Data siswa {$student->name} berhasil dihapus.");
    }

    /**
     * Portal Publik: Cek mandiri kehadiran siswa untuk Orang Tua & Siswa.
     */
    public function publicCheck(Request $request): View
    {
        $nis = $request->input('nis');
        $student = null;
        $attendances = collect();
        $stats = null;
        $schoolSetting = SchoolSetting::get();

        if ($nis) {
            $student = Student::with(['classroom.academicYear'])
                ->where('nis', trim($nis))
                ->orWhere('nisn', trim($nis))
                ->first();

            if ($student) {
                $totalHadir = $student->attendances->where('status', 'H')->count();
                $totalTerlambat = $student->attendances->where('status', 'T')->count();
                $totalSakit = $student->attendances->where('status', 'S')->count();
                $totalIzin = $student->attendances->where('status', 'I')->count();
                $totalAlpa = $student->attendances->where('status', 'A')->count();
                $totalHari = $totalHadir + $totalTerlambat + $totalSakit + $totalIzin + $totalAlpa;
                $persentase = $totalHari > 0 ? round((($totalHadir + $totalTerlambat) / $totalHari) * 100, 1) : 0;
                $totalPoin = $student->totalDisciplinePoints();

                $stats = compact('totalHadir', 'totalTerlambat', 'totalSakit', 'totalIzin', 'totalAlpa', 'totalHari', 'persentase', 'totalPoin');

                $attendances = $student->attendances()
                    ->with('attendanceSession')
                    ->orderByDesc('id')
                    ->take(10)
                    ->get();
            }
        }

        return view('students.public_check', compact('student', 'nis', 'attendances', 'stats', 'schoolSetting'));
    }
}
