<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Classroom;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RecapController extends Controller
{
    /**
     * Modul 3.5: Rekap Kehadiran per Siswa (FR-24 s.d FR-27).
     */
    public function studentRecap(Request $request): View
    {
        $classrooms = Classroom::with('academicYear')->orderBy('id')->get();
        $selectedClassroomId = $request->input('classroom_id');
        $selectedStudentId = $request->input('student_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        // Validasi tanggal awal <= tanggal akhir (BR-08 & Skenario 12)
        if ($startDate && $endDate && $startDate > $endDate) {
            session()->flash('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir. Filter rentang tanggal tidak valid dan ditolak.');
            $startDate = null;
            $endDate = null;
        }

        // Daftar siswa untuk dropdown filter
        $studentListQuery = Student::where('status', 'aktif');
        if ($selectedClassroomId) {
            $studentListQuery->where('classroom_id', $selectedClassroomId);
        }
        $studentsList = $studentListQuery->orderBy('name')->get();

        $selectedStudent = null;
        $attendances = collect();
        $summary = [
            'hadir' => 0,
            'terlambat' => 0,
            'sakit' => 0,
            'izin' => 0,
            'alpa' => 0,
            'total' => 0,
            'percentage' => 0,
        ];

        // Jika siswa dipilih atau dicari
        if ($selectedStudentId || $search) {
            $studentQuery = Student::with(['classroom.academicYear']);
            if ($selectedStudentId) {
                $studentQuery->where('id', $selectedStudentId);
            } elseif ($search) {
                $studentQuery->where(function ($q) use ($search) {
                    $q->where('nis', $search)->orWhere('name', 'like', "%{$search}%");
                });
            }

            $selectedStudent = $studentQuery->first();

            if ($selectedStudent) {
                $attQuery = Attendance::with(['attendanceSession.classroom'])
                    ->where('student_id', $selectedStudent->id)
                    ->whereHas('attendanceSession', function ($q) use ($startDate, $endDate) {
                        if ($startDate) {
                            $q->whereDate('attendance_date', '>=', $startDate);
                        }
                        if ($endDate) {
                            $q->whereDate('attendance_date', '<=', $endDate);
                        }
                    });

                $attendances = $attQuery->get()->sortByDesc(fn($a) => $a->attendanceSession->attendance_date);

                $summary['hadir'] = $attendances->where('status', 'H')->count();
                $summary['terlambat'] = $attendances->where('status', 'T')->count();
                $summary['sakit'] = $attendances->where('status', 'S')->count();
                $summary['izin'] = $attendances->where('status', 'I')->count();
                $summary['alpa'] = $attendances->where('status', 'A')->count();
                $summary['total'] = $summary['hadir'] + $summary['terlambat'] + $summary['sakit'] + $summary['izin'] + $summary['alpa'];

                // Hadir + Terlambat dihitung sebagai presensi hadir fisik
                if ($summary['total'] > 0) {
                    $summary['percentage'] = round((($summary['hadir'] + $summary['terlambat']) / $summary['total']) * 100, 1);
                }
            }
        }

        return view('recap.student', compact(
            'classrooms',
            'studentsList',
            'selectedClassroomId',
            'selectedStudentId',
            'selectedStudent',
            'attendances',
            'summary',
            'startDate',
            'endDate',
            'search'
        ));
    }

    /**
     * Modul 3.6: Rekap Kehadiran per Kelas (FR-28 s.d FR-32).
     */
    public function classRecap(Request $request): View
    {
        $classrooms = Classroom::with('academicYear')->orderBy('id')->get();
        $selectedClassroomId = $request->input('classroom_id', $classrooms->first()?->id);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        // Validasi tanggal awal <= tanggal akhir (BR-08 & Skenario 12)
        if ($startDate && $endDate && $startDate > $endDate) {
            session()->flash('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir. Filter rentang tanggal tidak valid dan ditolak.');
            $startDate = null;
            $endDate = null;
        }

        $selectedClassroom = null;
        $recapStudents = collect();
        $classAverages = [
            'total_hadir' => 0,
            'total_terlambat' => 0,
            'total_sakit' => 0,
            'total_izin' => 0,
            'total_alpa' => 0,
            'total_sesi' => 0,
            'avg_percentage' => 0,
        ];

        if ($selectedClassroomId) {
            $selectedClassroom = Classroom::with('academicYear')->find($selectedClassroomId);

            if ($selectedClassroom) {
                // Hitung total sesi absensi dalam filter
                $sessionQuery = AttendanceSession::where('classroom_id', $selectedClassroomId);
                if ($startDate) {
                    $sessionQuery->whereDate('attendance_date', '>=', $startDate);
                }
                if ($endDate) {
                    $sessionQuery->whereDate('attendance_date', '<=', $endDate);
                }
                $totalSessions = $sessionQuery->count();

                // Ambil siswa rombel ini
                $studentsQuery = Student::where('classroom_id', $selectedClassroomId);
                if ($search) {
                    $studentsQuery->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")->orWhere('nis', 'like', "%{$search}%");
                    });
                }
                $students = $studentsQuery->orderBy('name')->get();

                $totalPercentSum = 0;
                $activeStudentsCount = 0;

                foreach ($students as $student) {
                    $studentAttQuery = Attendance::where('student_id', $student->id)
                        ->whereHas('attendanceSession', function ($q) use ($selectedClassroomId, $startDate, $endDate) {
                            $q->where('classroom_id', $selectedClassroomId);
                            if ($startDate) {
                                $q->whereDate('attendance_date', '>=', $startDate);
                            }
                            if ($endDate) {
                                $q->whereDate('attendance_date', '<=', $endDate);
                            }
                        });

                    $h = (clone $studentAttQuery)->where('status', 'H')->count();
                    $t = (clone $studentAttQuery)->where('status', 'T')->count();
                    $s = (clone $studentAttQuery)->where('status', 'S')->count();
                    $i = (clone $studentAttQuery)->where('status', 'I')->count();
                    $a = (clone $studentAttQuery)->where('status', 'A')->count();
                    $total = $h + $t + $s + $i + $a;

                    $pct = ($total > 0) ? round((($h + $t) / $total) * 100, 1) : 0;

                    $classAverages['total_hadir'] += $h;
                    $classAverages['total_terlambat'] += $t;
                    $classAverages['total_sakit'] += $s;
                    $classAverages['total_izin'] += $i;
                    $classAverages['total_alpa'] += $a;
                    $totalPercentSum += $pct;
                    $activeStudentsCount++;

                    $recapStudents->push([
                        'student' => $student,
                        'hadir' => $h,
                        'terlambat' => $t,
                        'sakit' => $s,
                        'izin' => $i,
                        'alpa' => $a,
                        'total' => $total,
                        'percentage' => $pct,
                    ]);
                }

                $classAverages['total_sesi'] = $totalSessions;
                $classAverages['avg_percentage'] = ($activeStudentsCount > 0)
                    ? round($totalPercentSum / $activeStudentsCount, 1)
                    : 0;
            }
        }

        return view('recap.class', compact(
            'classrooms',
            'selectedClassroomId',
            'selectedClassroom',
            'recapStudents',
            'classAverages',
            'startDate',
            'endDate',
            'search'
        ));
    }

    /**
     * Ekspor rekapitulasi kelas ke file Excel (.xls).
     */
    public function exportClassExcel(Request $request): Response
    {
        $reportController = app(\App\Http\Controllers\ReportController::class);
        return $reportController->exportExcel($request);
    }
}
