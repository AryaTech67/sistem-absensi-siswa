<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\AttendanceAudit;
use App\Models\AttendanceSession;
use App\Models\Classroom;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    /**
     * Tampilkan riwayat sesi absensi dengan filter.
     */
    public function index(Request $request): View
    {
        $query = AttendanceSession::with(['classroom.academicYear', 'creator'])
            ->withCount([
                'attendances as hadir_count' => fn($q) => $q->where('status', 'H'),
                'attendances as terlambat_count' => fn($q) => $q->where('status', 'T'),
                'attendances as sakit_count' => fn($q) => $q->where('status', 'S'),
                'attendances as izin_count' => fn($q) => $q->where('status', 'I'),
                'attendances as alpa_count' => fn($q) => $q->where('status', 'A'),
                'attendances as total_students',
            ]);

        // Filter kelas
        if ($classroomId = $request->input('classroom_id')) {
            $query->where('classroom_id', $classroomId);
        }

        // Filter tanggal
        if ($date = $request->input('date')) {
            $query->whereDate('attendance_date', $date);
        }

        $sessions = $query->orderByDesc('attendance_date')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $classrooms = Classroom::with('academicYear')->orderBy('id')->get();

        return view('attendance.index', compact('sessions', 'classrooms'));
    }

    /**
     * Formulir pencatatan absensi massal (Sesuai Mockup PRD Halaman 13 & FR-17).
     */
    public function create(Request $request): View
    {
        $classrooms = Classroom::with('academicYear')->orderBy('id')->get();
        $selectedClassroomId = $request->input('classroom_id');
        $selectedDate = $request->input('date', Carbon::today()->toDateString());
        $sessionName = $request->input('session_name', 'Pagi');

        $students = collect();
        $existingSession = null;
        $selectedClassroom = null;

        if ($selectedClassroomId) {
            $selectedClassroom = Classroom::with('academicYear')->find($selectedClassroomId);

            if ($selectedClassroom) {
                // Cek apakah sesi absensi pada tanggal dan sesi ini sudah pernah dicatat (BR-04)
                $existingSession = AttendanceSession::where('classroom_id', $selectedClassroomId)
                    ->whereDate('attendance_date', $selectedDate)
                    ->where('session_name', $sessionName)
                    ->first();

                // Ambil siswa aktif di kelas tersebut
                $students = Student::where('classroom_id', $selectedClassroomId)
                    ->where('status', 'aktif')
                    ->orderBy('name')
                    ->get();
            }
        }

        return view('attendance.create', compact(
            'classrooms',
            'selectedClassroomId',
            'selectedClassroom',
            'selectedDate',
            'sessionName',
            'students',
            'existingSession'
        ));
    }

    /**
     * Simpan data sesi dan absensi siswa ke database (FR-19, BR-04).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'attendance_date' => ['required', 'date'],
            'session_name' => ['required', 'string', 'max:50'],
            'attendances' => ['required', 'array', 'min:1'],
            'attendances.*.student_id' => ['required', 'exists:students,id'],
            'attendances.*.status' => ['required', 'in:H,T,S,I,A'],
            'attendances.*.notes' => ['nullable', 'string', 'max:255'],
        ], [
            'classroom_id.required' => 'Rombongan belajar wajib dipilih.',
            'attendance_date.required' => 'Tanggal absensi wajib ditentukan.',
            'attendances.required' => 'Belum ada siswa yang diabsen.',
        ]);

        $classroomId = $request->input('classroom_id');
        $attendanceDate = $request->input('attendance_date');
        $sessionName = $request->input('session_name');

        // Mencegah duplikasi data absensi (BR-04)
        $existingSession = AttendanceSession::where('classroom_id', $classroomId)
            ->whereDate('attendance_date', $attendanceDate)
            ->where('session_name', $sessionName)
            ->first();

        if ($existingSession) {
            return back()->with('error', "Absensi untuk kelas ini pada tanggal {$attendanceDate} ({$sessionName}) sudah pernah dicatat sebelumnya. Silakan gunakan tombol edit jika ingin memperbaiki catatan.")->withInput();
        }

        DB::transaction(function () use ($request, $classroomId, $attendanceDate, $sessionName) {
            $session = AttendanceSession::create([
                'classroom_id' => $classroomId,
                'attendance_date' => $attendanceDate,
                'session_name' => $sessionName,
                'created_by' => Auth::id(),
            ]);

            foreach ($request->input('attendances') as $att) {
                Attendance::create([
                    'attendance_session_id' => $session->id,
                    'student_id' => $att['student_id'],
                    'status' => $att['status'],
                    'notes' => $att['notes'] ?? null,
                    'recorded_by' => Auth::id(),
                ]);
            }
        });

        return redirect()->route('attendance.index')
            ->with('success', 'Data absensi berhasil disimpan!');
    }

    /**
     * Tampilkan detail hasil absensi suatu sesi.
     */
    public function show($id): View
    {
        $attendanceSession = AttendanceSession::with([
            'classroom.academicYear',
            'creator',
            'attendances.student',
            'attendances.audits.user'
        ])->findOrFail($id);

        return view('attendance.show', compact('attendanceSession'));
    }

    /**
     * Cetak lembar presensi sesi ini (PDF / Print View A4).
     */
    public function print($id): View
    {
        $attendanceSession = AttendanceSession::with([
            'classroom.academicYear',
            'creator',
            'attendances.student'
        ])->findOrFail($id);

        $counts = [
            'H' => $attendanceSession->attendances->where('status', 'H')->count(),
            'S' => $attendanceSession->attendances->where('status', 'S')->count(),
            'I' => $attendanceSession->attendances->where('status', 'I')->count(),
            'A' => $attendanceSession->attendances->where('status', 'A')->count(),
            'total' => $attendanceSession->attendances->count(),
        ];
        $counts['percentage'] = $counts['total'] > 0 ? round(($counts['H'] / $counts['total']) * 100, 1) : 0;

        return view('attendance.print', compact('attendanceSession', 'counts'));
    }

    /**
     * Ekspor presensi sesi ini ke file Excel (.xls).
     */
    public function exportExcel($id): Response
    {
        $attendanceSession = AttendanceSession::with([
            'classroom.academicYear',
            'creator',
            'attendances.student'
        ])->findOrFail($id);

        $classroomName = str_replace(['/', '\\', ' '], '_', $attendanceSession->classroom->name);
        $dateStr = $attendanceSession->attendance_date->format('Y-m-d');
        $filename = "Absensi_{$classroomName}_{$dateStr}.xls";

        $counts = [
            'H' => $attendanceSession->attendances->where('status', 'H')->count(),
            'S' => $attendanceSession->attendances->where('status', 'S')->count(),
            'I' => $attendanceSession->attendances->where('status', 'I')->count(),
            'A' => $attendanceSession->attendances->where('status', 'A')->count(),
            'total' => $attendanceSession->attendances->count(),
        ];
        $counts['percentage'] = $counts['total'] > 0 ? round(($counts['H'] / $counts['total']) * 100, 1) : 0;

        $content = view('attendance.excel', compact('attendanceSession', 'counts'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Formulir perbaikan/edit absensi (FR-20).
     */
    public function edit($id): View
    {
        $attendanceSession = AttendanceSession::with([
            'classroom.academicYear',
            'attendances.student'
        ])->findOrFail($id);

        return view('attendance.edit', compact('attendanceSession'));
    }

    /**
     * Simpan perbaikan absensi dan catat log audit (BR-12).
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $attendanceSession = AttendanceSession::findOrFail($id);

        $request->validate([
            'attendances' => ['required', 'array'],
            'attendances.*.status' => ['required', 'in:H,T,S,I,A'],
            'attendances.*.notes' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($request, $attendanceSession) {
            foreach ($request->input('attendances') as $attendanceId => $data) {
                $attendance = Attendance::where('attendance_session_id', $attendanceSession->id)
                    ->where('id', $attendanceId)
                    ->first();

                if ($attendance) {
                    $oldStatus = $attendance->status;
                    $newStatus = $data['status'];

                    // Jika status berubah, catat ke log audit (Halaman 10-12 PRD)
                    if ($oldStatus !== $newStatus) {
                        AttendanceAudit::create([
                            'attendance_id' => $attendance->id,
                            'old_status' => $oldStatus,
                            'new_status' => $newStatus,
                            'changed_by' => Auth::id(),
                            'changed_at' => now(),
                        ]);
                    }

                    $attendance->update([
                        'status' => $newStatus,
                        'notes' => $data['notes'] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('attendance.show', ['attendance' => $attendanceSession->id])
            ->with('success', 'Data absensi berhasil diperbarui dan riwayat perubahan tercatat.');
    }

    /**
     * Hapus sesi absensi.
     */
    public function destroy($id): RedirectResponse
    {
        $attendanceSession = AttendanceSession::with('classroom')->findOrFail($id);
        $classroomName = $attendanceSession->classroom->name;
        $date = $attendanceSession->attendance_date->format('d/m/Y');

        $attendanceSession->delete();

        return redirect()->route('attendance.index')
            ->with('success', "Sesi absensi {$classroomName} tanggal {$date} berhasil dihapus.");
    }

    /**
     * Toggle status penyelesaian tugas kompensasi kedisiplinan siswa.
     */
    public function toggleCompensation(Attendance $attendance): RedirectResponse
    {
        $newStatus = $attendance->compensation_status === 'completed' ? 'pending' : 'completed';
        $attendance->update(['compensation_status' => $newStatus]);

        return redirect()->back()
            ->with('success', 'Status tugas kompensasi siswa ' . $attendance->student->name . ' berhasil diperbarui menjadi ' . ($newStatus === 'completed' ? 'Selesai' : 'Belum Selesai') . '.');
    }
}
