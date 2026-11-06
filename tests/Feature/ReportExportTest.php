<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_print_and_export_endpoints_work_properly(): void
    {
        $this->seed();

        $user = User::first();
        $classroom = Classroom::first();
        $student = Student::first();

        // Buat dummy session absensi jika belum ada
        $session = AttendanceSession::firstOrCreate(
            [
                'classroom_id' => $classroom->id,
                'attendance_date' => now()->toDateString(),
                'session_name' => 'Pagi',
            ],
            [
                'created_by' => $user->id,
            ]
        );

        if ($student) {
            Attendance::firstOrCreate([
                'attendance_session_id' => $session->id,
                'student_id' => $student->id,
            ], [
                'status' => 'H',
            ]);
        }

        // 1. Test Attendance Session Print (PDF View)
        $res = $this->actingAs($user)->get(route('attendance.print', ['attendance' => $session->id]));
        $res->assertStatus(200);

        // 2. Test Attendance Session Export Excel
        $resExcel = $this->actingAs($user)->get(route('attendance.export-excel', ['attendance' => $session->id]));
        $resExcel->assertStatus(200);
        $resExcel->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');

        // 3. Test Reports Print View
        $resReportPrint = $this->actingAs($user)->get(route('reports.print', ['classroom_id' => $classroom->id]));
        $resReportPrint->assertStatus(200);

        // 4. Test Reports Export Excel
        $resReportExcel = $this->actingAs($user)->get(route('reports.export-excel', ['classroom_id' => $classroom->id]));
        $resReportExcel->assertStatus(200);
        $resReportExcel->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');

        // 5. Test Recap Class Export Excel
        $resRecapExcel = $this->actingAs($user)->get(route('recap.class.export-excel', ['classroom_id' => $classroom->id]));
        $resRecapExcel->assertStatus(200);
        $resRecapExcel->assertHeader('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');
    }
}
