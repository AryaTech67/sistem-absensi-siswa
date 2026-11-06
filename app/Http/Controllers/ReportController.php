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
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Tampilkan halaman filter laporan absensi.
     */
    public function index(Request $request): View
    {
        $classrooms = Classroom::with('academicYear')->orderBy('id')->get();
        $classroomId = $request->input('classroom_id');
        $studentId = $request->input('student_id');
        $startDate = $request->input('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        if ($startDate && $endDate && $startDate > $endDate) {
            $startDate = Carbon::today()->startOfMonth()->toDateString();
            $endDate = Carbon::today()->toDateString();
        }

        // Validasi tanggal awal <= tanggal akhir (BR-08 & Skenario 12)
        if ($startDate && $endDate && $startDate > $endDate) {
            session()->flash('error', 'Tanggal awal tidak boleh lebih besar dari tanggal akhir. Filter rentang tanggal tidak valid dan ditolak.');
            $startDate = Carbon::today()->startOfMonth()->toDateString();
            $endDate = Carbon::today()->toDateString();
        }

        $studentsQuery = Student::where('status', 'aktif');
        if ($classroomId) {
            $studentsQuery->where('classroom_id', $classroomId);
        }
        $studentsList = $studentsQuery->orderBy('name')->get();

        $reportData = $this->fetchReportData($classroomId, $studentId, $startDate, $endDate);

        return view('reports.index', array_merge($reportData, [
            'classrooms' => $classrooms,
            'studentsList' => $studentsList,
            'classroomId' => $classroomId,
            'studentId' => $studentId,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]));
    }

    /**
     * Halaman cetak laporan (Print View untuk Browser Print / PDF).
     */
    public function print(Request $request): View
    {
        $classroomId = $request->input('classroom_id');
        $studentId = $request->input('student_id');
        $startDate = $request->input('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        if ($startDate && $endDate && $startDate > $endDate) {
            $startDate = Carbon::today()->startOfMonth()->toDateString();
            $endDate = Carbon::today()->toDateString();
        }

        $reportData = $this->fetchReportData($classroomId, $studentId, $startDate, $endDate);
        $selectedClassroom = $classroomId ? Classroom::with('academicYear')->find($classroomId) : null;
        $selectedStudent = $studentId ? Student::find($studentId) : null;

        return view('reports.print', array_merge($reportData, [
            'selectedClassroom' => $selectedClassroom,
            'selectedStudent' => $selectedStudent,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'printDate' => Carbon::now()->isoFormat('D MMMM Y'),
        ]));
    }

    /**
     * Ekspor laporan ke format Excel spreadsheet (.xls) berformat tabel rapi.
     */
    public function exportExcel(Request $request): Response
    {
        $classroomId = $request->input('classroom_id');
        $studentId = $request->input('student_id');
        $startDate = $request->input('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        if ($startDate && $endDate && $startDate > $endDate) {
            $startDate = Carbon::today()->startOfMonth()->toDateString();
            $endDate = Carbon::today()->toDateString();
        }

        $reportData = $this->fetchReportData($classroomId, $studentId, $startDate, $endDate);
        $selectedClassroom = $classroomId ? Classroom::with('academicYear')->find($classroomId) : null;
        $selectedStudent = $studentId ? Student::find($studentId) : null;

        $clsName = $selectedClassroom ? str_replace(['/', '\\', ' '], '_', $selectedClassroom->name) : 'Semua_Kelas';
        $filename = "Laporan_Absensi_{$clsName}_{$startDate}_{$endDate}.xls";

        $content = view('reports.excel', array_merge($reportData, [
            'selectedClassroom' => $selectedClassroom,
            'selectedStudent' => $selectedStudent,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'printDate' => Carbon::now()->isoFormat('D MMMM Y'),
        ]))->render();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Ekspor laporan ke format CSV / Excel (FR-38).
     */
    public function exportCsv(Request $request): Response
    {
        $classroomId = $request->input('classroom_id');
        $studentId = $request->input('student_id');
        $startDate = $request->input('start_date', Carbon::today()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());
        if ($startDate && $endDate && $startDate > $endDate) {
            $startDate = Carbon::today()->startOfMonth()->toDateString();
            $endDate = Carbon::today()->toDateString();
        }

        $reportData = $this->fetchReportData($classroomId, $studentId, $startDate, $endDate);
        $filename = 'Laporan_Absensi_' . date('Ymd_His') . '.csv';

        $handle = fopen('php://memory', 'w');
        // Tambahkan BOM agar karakter terbaca baik di Microsoft Excel
        fputs($handle, "\xEF\xBB\xBF");

        // Header CSV
        fputcsv($handle, ['No', 'NIS', 'Nama Siswa', 'Kelas', 'Hadir (H)', 'Terlambat (T)', 'Sakit (S)', 'Izin (I)', 'Alpa (A)', 'Total Pertemuan', '% Kehadiran']);

        $no = 1;
        foreach ($reportData['recapList'] as $row) {
            fputcsv($handle, [
                $no++,
                $row['student']->nis,
                $row['student']->name,
                $row['student']->classroom->name ?? '-',
                $row['hadir'],
                $row['terlambat'],
                $row['sakit'],
                $row['izin'],
                $row['alpa'],
                $row['total'],
                $row['percentage'] . '%',
            ]);
        }

        fseek($handle, 0);

        return response(stream_get_contents($handle), 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Helper untuk mengambil data rekapitulasi berdasarkan filter.
     */
    private function fetchReportData(?string $classroomId, ?string $studentId, ?string $startDate, ?string $endDate): array
    {
        $studentsQuery = Student::with('classroom')->where('status', 'aktif');

        if ($classroomId) {
            $studentsQuery->where('classroom_id', $classroomId);
        }

        if ($studentId) {
            $studentsQuery->where('id', $studentId);
        }

        $students = $studentsQuery->orderBy('classroom_id')->orderBy('name')->get();
        $recapList = [];
        $totalH = 0;
        $totalT = 0;
        $totalS = 0;
        $totalI = 0;
        $totalA = 0;

        foreach ($students as $student) {
            $attQuery = Attendance::where('student_id', $student->id)
                ->whereHas('attendanceSession', function ($q) use ($startDate, $endDate) {
                    if ($startDate) {
                        $q->whereDate('attendance_date', '>=', $startDate);
                    }
                    if ($endDate) {
                        $q->whereDate('attendance_date', '<=', $endDate);
                    }
                });

            $h = (clone $attQuery)->where('status', 'H')->count();
            $t = (clone $attQuery)->where('status', 'T')->count();
            $s = (clone $attQuery)->where('status', 'S')->count();
            $i = (clone $attQuery)->where('status', 'I')->count();
            $a = (clone $attQuery)->where('status', 'A')->count();
            $total = $h + $t + $s + $i + $a;

            $pct = ($total > 0) ? round((($h + $t) / $total) * 100, 1) : 0;

            $totalH += $h;
            $totalT += $t;
            $totalS += $s;
            $totalI += $i;
            $totalA += $a;

            $recapList[] = [
                'student' => $student,
                'hadir' => $h,
                'terlambat' => $t,
                'sakit' => $s,
                'izin' => $i,
                'alpa' => $a,
                'total' => $total,
                'percentage' => $pct,
            ];
        }

        $grandTotal = $totalH + $totalT + $totalS + $totalI + $totalA;
        $overallPercentage = ($grandTotal > 0) ? round((($totalH + $totalT) / $grandTotal) * 100, 1) : 0;

        return [
            'recapList' => $recapList,
            'summary' => [
                'hadir' => $totalH,
                'terlambat' => $totalT,
                'sakit' => $totalS,
                'izin' => $totalI,
                'alpa' => $totalA,
                'total' => $grandTotal,
                'overall_percentage' => $overallPercentage,
            ]
        ];
    }
}
