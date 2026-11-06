<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Classroom;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman utama dashboard.
     */
    public function index(Request $request): View
    {
        $today = Carbon::today()->toDateString();

        // Tahun ajaran aktif
        $activeYear = AcademicYear::where('is_active', true)->first();

        // Statistik Master Data
        $totalStudents = Student::where('status', 'aktif')->count();
        $totalClassrooms = Classroom::count();

        // Absensi Hari Ini
        $todaySessionIds = AttendanceSession::whereDate('attendance_date', $today)->pluck('id');

        $todayStats = Attendance::whereIn('attendance_session_id', $todaySessionIds)
            ->selectRaw("
                SUM(CASE WHEN status = 'H' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = 'T' THEN 1 ELSE 0 END) as terlambat,
                SUM(CASE WHEN status = 'S' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = 'I' THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = 'A' THEN 1 ELSE 0 END) as alpa,
                COUNT(*) as total
            ")
            ->first();

        $hadirToday = $todayStats->hadir ?? 0;
        $terlambatToday = $todayStats->terlambat ?? 0;
        $sakitToday = $todayStats->sakit ?? 0;
        $izinToday = $todayStats->izin ?? 0;
        $alpaToday = $todayStats->alpa ?? 0;
        $totalRecordedToday = $todayStats->total ?? 0;

        $attendanceRateToday = ($totalRecordedToday > 0)
            ? round((($hadirToday + $terlambatToday) / $totalRecordedToday) * 100, 1)
            : 0;

        // Tren Absensi 7 Hari Belakang (untuk grafik garis / bar)
        $sevenDaysAgo = Carbon::today()->subDays(6)->toDateString();
        $trendData = DB::table('attendance_sessions')
            ->join('attendances', 'attendance_sessions.id', '=', 'attendances.attendance_session_id')
            ->whereDate('attendance_sessions.attendance_date', '>=', $sevenDaysAgo)
            ->whereDate('attendance_sessions.attendance_date', '<=', $today)
            ->select(
                DB::raw("DATE(attendance_sessions.attendance_date) as attendance_date"),
                DB::raw("SUM(CASE WHEN attendances.status = 'H' THEN 1 ELSE 0 END) as hadir"),
                DB::raw("SUM(CASE WHEN attendances.status = 'T' THEN 1 ELSE 0 END) as terlambat"),
                DB::raw("SUM(CASE WHEN attendances.status = 'S' THEN 1 ELSE 0 END) as sakit"),
                DB::raw("SUM(CASE WHEN attendances.status = 'I' THEN 1 ELSE 0 END) as izin"),
                DB::raw("SUM(CASE WHEN attendances.status = 'A' THEN 1 ELSE 0 END) as alpa")
            )
            ->groupBy(DB::raw("DATE(attendance_sessions.attendance_date)"))
            ->orderBy(DB::raw("DATE(attendance_sessions.attendance_date)"), 'asc')
            ->get();

        // Sesi Absensi Terbaru
        $recentSessions = AttendanceSession::with(['classroom', 'creator'])
            ->withCount([
                'attendances as hadir_count' => fn($q) => $q->where('status', 'H'),
                'attendances as total_count'
            ])
            ->orderByDesc('attendance_date')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        // Cek kelas yang belum absensi hari ini
        $classesWithAttendanceToday = AttendanceSession::whereDate('attendance_date', $today)
            ->pluck('classroom_id')
            ->toArray();

        $pendingClassesToday = Classroom::whereNotIn('id', $classesWithAttendanceToday)->get();

        $totalUsers = \App\Models\User::count();
        $totalAttendanceMonth = AttendanceSession::whereMonth('attendance_date', Carbon::now()->month)
            ->whereYear('attendance_date', Carbon::now()->year)
            ->count();

        return view('dashboard.index', compact(
            'activeYear',
            'totalStudents',
            'totalClassrooms',
            'totalUsers',
            'totalAttendanceMonth',
            'hadirToday',
            'terlambatToday',
            'sakitToday',
            'izinToday',
            'alpaToday',
            'totalRecordedToday',
            'attendanceRateToday',
            'trendData',
            'recentSessions',
            'pendingClassesToday'
        ));
    }
}
