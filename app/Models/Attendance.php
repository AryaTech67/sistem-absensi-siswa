<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_session_id',
        'student_id',
        'status',
        'notes',
        'recorded_by',
        'late_minutes',
        'missed_periods',
        'discipline_points',
        'compensation_task',
        'compensation_status',
        'tap_out_at',
    ];

    protected $casts = [
        'tap_out_at' => 'datetime',
        'late_minutes' => 'integer',
        'discipline_points' => 'integer',
    ];

    /**
     * Relasi ke sesi absensi.
     */
    public function attendanceSession(): BelongsTo
    {
        return $this->belongsTo(AttendanceSession::class);
    }

    /**
     * Relasi ke data siswa.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relasi ke petugas yang mencatat.
     */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Relasi ke riwayat audit perubahan absensi.
     */
    public function audits(): HasMany
    {
        return $this->hasMany(AttendanceAudit::class);
    }

    /**
     * Helper label status kehadiran.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'H' => 'Hadir',
            'T' => 'Terlambat',
            'S' => 'Sakit',
            'I' => 'Izin',
            'A' => 'Alpa',
            default => '-',
        };
    }
}
