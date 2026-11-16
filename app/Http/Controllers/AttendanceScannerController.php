<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Classroom;
use App\Models\SchoolSetting;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceScannerController extends Controller
{
    /**
     * Tampilkan halaman scanner presensi QR Code & Barcode.
     */
    public function index(Request $request): View
    {
        $classrooms = Classroom::with('academicYear')->withCount('students')->orderBy('name')->get();
        $selectedClassroomId = $request->input('classroom_id');
        $sessionType = $request->input('session_type', 'masuk'); // 'masuk' atau 'pulang'
        $selectedClassroom = null;
        $session = null;
        $students = collect();
        $attendances = collect();
        $schoolSetting = SchoolSetting::get();
        $stats = [
            'total' => 0,
            'hadir' => 0,
            'terlambat' => 0,
            'sakit' => 0,
            'izin' => 0,
            'alpa' => 0,
            'percentage' => 0,
        ];

        if ($selectedClassroomId) {
            $selectedClassroom = Classroom::with(['academicYear', 'waliKelas'])->find($selectedClassroomId);

            if ($selectedClassroom) {
                $today = now()->toDateString();
                $sessionName = $sessionType === 'pulang' ? 'Sore / Pulang' : 'Pagi / Harian';

                // Cari atau buat otomatis sesi absensi harian hari ini
                $session = AttendanceSession::where('classroom_id', $selectedClassroom->id)
                    ->whereDate('attendance_date', $today)
                    ->where('session_name', $sessionName)
                    ->first();

                if (!$session) {
                    $session = AttendanceSession::create([
                        'classroom_id' => $selectedClassroom->id,
                        'attendance_date' => $today,
                        'session_name' => $sessionName,
                        'created_by' => auth()->id(),
                    ]);
                }

                $students = Student::where('classroom_id', $selectedClassroom->id)
                    ->where('status', 'aktif')
                    ->orderBy('name')
                    ->get();

                $attendances = Attendance::where('attendance_session_id', $session->id)
                    ->get()
                    ->keyBy('student_id');

                $total = $students->count();
                $hadir = $attendances->where('status', 'H')->count();
                $terlambat = $attendances->where('status', 'T')->count();
                $sakit = $attendances->where('status', 'S')->count();
                $izin = $attendances->where('status', 'I')->count();
                $alpa = $attendances->where('status', 'A')->count();
                $percentage = $total > 0 ? round((($hadir + $terlambat) / $total) * 100, 1) : 0;

                $stats = compact('total', 'hadir', 'terlambat', 'sakit', 'izin', 'alpa', 'percentage');

                // Data JSON ringkas untuk auto-search & instant process di frontend
                $studentsJson = $students->map(function ($st) use ($attendances) {
                    $rec = $attendances->get($st->id);
                    return [
                        'id' => $st->id,
                        'nis' => $st->nis,
                        'name' => $st->name,
                        'gender' => $st->gender,
                        'photo_url' => $st->photo_url,
                        'status' => $rec ? $rec->status : null,
                        'status_label' => $rec ? $rec->status_label : 'Belum',
                        'time' => $rec ? $rec->updated_at->format('H:i') : '-',
                    ];
                });
            }
        }

        return view('attendance.scanner', compact(
            'classrooms',
            'selectedClassroom',
            'session',
            'sessionType',
            'students',
            'attendances',
            'stats',
            'schoolSetting',
            'studentsJson'
        ));
    }

    /**
     * Endpoint AJAX untuk memproses scan QR Code / Barcode NIS dengan perhitungan keterlambatan cerdas.
     */
    public function scan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:100'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'session_type' => ['nullable', 'string', 'in:masuk,pulang'],
            'status' => ['nullable', 'string', 'in:H,T,S,I,A'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'code.required' => 'Identitas barcode / NIS / nama siswa wajib diisi.',
            'classroom_id.required' => 'Kelas wajib dipilih.',
            'classroom_id.exists' => 'Kelas tidak terdaftar di database.',
        ]);

        $code = trim($validated['code']);
        $classroomId = $validated['classroom_id'];
        $sessionType = $validated['session_type'] ?? 'masuk';
        $requestedStatus = $validated['status'] ?? 'H';

        // 1. Cari siswa berdasarkan NIS, NISN, atau ID
        $student = Student::with('classroom')->where(function ($q) use ($code) {
            $q->where('nis', $code)
              ->orWhere('nisn', $code)
              ->orWhere('id', $code);
        })->first();

        // 2. Jika belum ketemu dengan NIS, cari berdasarkan nama siswa di kelas yang dipilih
        if (!$student && strlen($code) >= 2) {
            $student = Student::with('classroom')
                ->where('classroom_id', $classroomId)
                ->where('name', 'like', "%{$code}%")
                ->first();
        }

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => "Data siswa dengan identitas/kode [{$code}] tidak ditemukan.",
            ], 404);
        }

        // Validasi apakah siswa terdaftar di rombel yang dipilih
        if ($student->classroom_id != $classroomId) {
            return response()->json([
                'success' => false,
                'message' => "Siswa {$student->name} ({$student->nis}) terdaftar di rombel {$student->classroom->name}, bukan di kelas ini.",
                'student' => [
                    'id' => $student->id,
                    'nis' => $student->nis,
                    'name' => $student->name,
                    'classroom' => $student->classroom->name ?? '-',
                ]
            ], 422);
        }

        $today = now()->toDateString();
        $sessionName = $sessionType === 'pulang' ? 'Sore / Pulang' : 'Pagi / Harian';

        $session = AttendanceSession::where('classroom_id', $classroomId)
            ->whereDate('attendance_date', $today)
            ->where('session_name', $sessionName)
            ->first();

        if (!$session) {
            $session = AttendanceSession::create([
                'classroom_id' => $classroomId,
                'attendance_date' => $today,
                'session_name' => $sessionName,
                'created_by' => auth()->id(),
            ]);
        }

        $now = now();
        $schoolSetting = SchoolSetting::get();

        if (in_array($requestedStatus, ['S', 'I', 'A'])) {
            // Pencatatan Manual Sakit / Izin / Alpa
            $labels = ['S' => 'Sakit', 'I' => 'Izin', 'A' => 'Alpa'];
            $defaultNote = $validated['notes'] ?? ($labels[$requestedStatus] . " (Dicatat Manual)");

            $attendance = Attendance::updateOrCreate(
                [
                    'attendance_session_id' => $session->id,
                    'student_id' => $student->id,
                ],
                [
                    'status' => $requestedStatus,
                    'late_minutes' => 0,
                    'missed_periods' => null,
                    'discipline_points' => 0,
                    'compensation_task' => null,
                    'compensation_status' => 'completed',
                    'notes' => $defaultNote,
                    'recorded_by' => auth()->id(),
                ]
            );

            $message = "Status {$student->name} berhasil dicatat sebagai {$labels[$requestedStatus]}.";
            $isLate = false;
            $lateMinutes = 0;
            $missedPeriods = null;
            $disciplinePoints = 0;
            $compensationTask = null;
            $compensationStatus = 'completed';
        } elseif ($sessionType === 'pulang') {
            // Sesi Pulang: Catat tap out
            $attendance = Attendance::updateOrCreate(
                [
                    'attendance_session_id' => $session->id,
                    'student_id' => $student->id,
                ],
                [
                    'status' => 'H',
                    'tap_out_at' => $now,
                    'notes' => 'Presensi Pulang (' . $now->format('H:i') . ' WIB)',
                    'recorded_by' => auth()->id(),
                ]
            );

            $message = "Presensi Pulang Berhasil! {$student->name} tercatat pulang pukul {$now->format('H:i')} WIB.";
            $isLate = false;
            $lateMinutes = 0;
            $missedPeriods = null;
            $disciplinePoints = 0;
            $compensationTask = null;
            $compensationStatus = 'completed';
        } else {
            // Sesi Masuk: Hitung keterlambatan, jam pelajaran terlewat, & poin pelanggaran
            $entryTimeStr = $schoolSetting->entry_time ?? '07:15';
            $tolerance = (int) ($schoolSetting->tolerance_minutes ?? 0);

            $entryThreshold = Carbon::createFromTimeString($entryTimeStr)->addMinutes($tolerance);

            $isLate = false;
            $lateMinutes = 0;
            $status = 'H';
            $missedPeriods = null;
            $disciplinePoints = 0;
            $compensationTask = null;
            $compensationStatus = 'completed';

            if ($now->greaterThan($entryThreshold)) {
                $isLate = true;
                $status = 'T'; // Terlambat
                $lateMinutes = (int) Carbon::createFromTimeString($entryTimeStr)->diffInMinutes($now);

                // Analisis Jam Pelajaran (JP) yang Terlewat
                $periods = $schoolSetting->getPeriods();
                $missed = [];
                foreach ($periods as $p) {
                    $pEnd = Carbon::createFromTimeString($p['end']);
                    $pStart = Carbon::createFromTimeString($p['start']);
                    if ($now->greaterThanOrEqualTo($pEnd)) {
                        $missed[] = "Jam Ke-{$p['period']}";
                    } elseif ($now->greaterThan($pStart)) {
                        $missed[] = "Jam Ke-{$p['period']} (Sedang Berjalan)";
                    }
                }

                if (count($missed) > 0) {
                    $missedPeriods = "Tertinggal: " . implode(', ', $missed);
                } else {
                    $missedPeriods = "Terlambat Masuk (Sebelum Jam 1)";
                }

                // Hitung Poin Pelanggaran & Tugas Kompensasi Edukatif
                if ($lateMinutes <= 15) {
                    $disciplinePoints = 5;
                    $compensationTask = 'Teguran Guru Piket & Surat Izin Masuk Kelas';
                    $compensationStatus = 'completed';
                } elseif ($lateMinutes <= 45) {
                    $disciplinePoints = 10;
                    $compensationTask = 'Resume Materi Jam Pelajaran Ke-1 + Literasi Perpustakaan';
                    $compensationStatus = 'pending';
                } else {
                    $disciplinePoints = 15;
                    $compensationTask = 'Tugas Kompensasi Mapel Jam 1-2 & Peringatan Wali Kelas';
                    $compensationStatus = 'pending';
                }
            }

            $attendance = Attendance::updateOrCreate(
                [
                    'attendance_session_id' => $session->id,
                    'student_id' => $student->id,
                ],
                [
                    'status' => $status,
                    'late_minutes' => $lateMinutes,
                    'missed_periods' => $missedPeriods,
                    'discipline_points' => $disciplinePoints,
                    'compensation_task' => $compensationTask,
                    'compensation_status' => $compensationStatus,
                    'notes' => $isLate 
                        ? "Terlambat {$lateMinutes} menit ({$missedPeriods}) — {$compensationTask}"
                        : "Tepat Waktu (" . $now->format('H:i') . " WIB)",
                    'recorded_by' => auth()->id(),
                ]
            );

            if ($isLate) {
                $message = "⚠️ KETERLAMBATAN TERDETEKSI: {$student->name} telat {$lateMinutes} menit ({$missedPeriods}). Sanksi: {$compensationTask}";
            } else {
                $message = "Presensi Berhasil! {$student->name} hadir tepat waktu.";
            }
        }

        // Frekuensi telat bulan ini & evaluasi SP / panggilan orang tua
        $lateCountMonth = $student->lateCountThisMonth();
        $totalDisciplinePoints = $student->totalDisciplinePoints();
        $needsParentCall = $student->needsParentCall();

        // Template Pesan WhatsApp ke Orang Tua
        $parentPhone = $student->parent_phone;
        $parentWhatsappUrl = null;
        if ($parentPhone) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $parentPhone);
            if (str_starts_with($cleanPhone, '0')) {
                $cleanPhone = '62' . substr($cleanPhone, 1);
            }
            $statusText = $isLate 
                ? "TERLAMBAT {$lateMinutes} menit (Masuk pukul {$now->format('H:i')} WIB). {$missedPeriods}." 
                : "telah hadir di sekolah TEPAT WAKTU pukul {$now->format('H:i')} WIB.";

            $waText = urlencode("Yth. Orang Tua/Wali dari {$student->name},\n\nMemberitahukan bahwa ananda {$statusText}\n\nTerima kasih atas perhatian dan kerja samanya.\n— Tata Tertib {$schoolSetting->school_name}");
            $parentWhatsappUrl = "https://wa.me/{$cleanPhone}?text={$waText}";
        }

        // Hitung ulang statistik kelas hari ini
        $totalStudents = Student::where('classroom_id', $classroomId)->where('status', 'aktif')->count();
        $attendances = Attendance::where('attendance_session_id', $session->id)->get();
        $hadir = $attendances->where('status', 'H')->count();
        $terlambat = $attendances->where('status', 'T')->count();
        $sakit = $attendances->where('status', 'S')->count();
        $izin = $attendances->where('status', 'I')->count();
        $alpa = $attendances->where('status', 'A')->count();
        $percentage = $totalStudents > 0 ? round((($hadir + $terlambat) / $totalStudents) * 100, 1) : 0;

        return response()->json([
            'success' => true,
            'message' => $message,
            'student' => [
                'id' => $student->id,
                'nis' => $student->nis,
                'name' => $student->name,
                'gender' => $student->gender,
                'classroom' => $student->classroom->name,
                'status' => $attendance->status,
                'status_label' => $attendance->status_label,
                'is_late' => $isLate,
                'late_minutes' => $lateMinutes,
                'missed_periods' => $missedPeriods,
                'discipline_points' => $disciplinePoints,
                'compensation_task' => $compensationTask,
                'compensation_status' => $compensationStatus,
                'late_count_month' => $lateCountMonth,
                'total_discipline_points' => $totalDisciplinePoints,
                'needs_parent_call' => $needsParentCall,
                'parent_name' => $student->parent_name,
                'parent_phone' => $student->parent_phone,
                'parent_whatsapp_url' => $parentWhatsappUrl,
                'photo_url' => $student->photo_url,
            ],
            'time' => $now->format('H:i:s'),
            'stats' => [
                'total' => $totalStudents,
                'hadir' => $hadir,
                'terlambat' => $terlambat,
                'sakit' => $sakit,
                'izin' => $izin,
                'alpa' => $alpa,
                'percentage' => $percentage,
            ],
        ]);
    }
}
