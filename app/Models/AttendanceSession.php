<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'attendance_date',
        'session_name',
        'created_by',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    /**
     * Relasi ke rombel (kelas).
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Relasi ke user pembuat sesi.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke catatan kehadiran tiap siswa pada sesi ini.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
