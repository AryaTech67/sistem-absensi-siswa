<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'nisn',
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'photo',
        'classroom_id',
        'status',
        'parent_name',
        'parent_phone',
    ];

    /**
     * Dapatkan URL foto profil/kartu siswa.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if ($this->photo && file_exists(public_path($this->photo))) {
            return asset($this->photo);
        }
        return null;
    }

    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Relasi ke rombongan belajar (kelas).
     */
    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    /**
     * Relasi ke semua catatan absensi siswa.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Cek apakah siswa berstatus aktif.
     */
    public function isActive(): bool
    {
        return $this->status === 'aktif';
    }

    /**
     * Total poin pelanggaran kedisiplinan yang terkumpul.
     */
    public function totalDisciplinePoints(): int
    {
        return (int) $this->attendances()->sum('discipline_points');
    }

    /**
     * Frekuensi keterlambatan bulan ini.
     */
    public function lateCountThisMonth(): int
    {
        return (int) $this->attendances()
            ->where('status', 'T')
            ->whereHas('attendanceSession', function ($q) {
                $q->whereYear('attendance_date', now()->year)
                  ->whereMonth('attendance_date', now()->month);
            })
            ->count();
    }

    /**
     * Cek apakah siswa memerlukan panggilan orang tua / BK (telat >= 3x bulan ini atau poin >= 30).
     */
    public function needsParentCall(): bool
    {
        return $this->lateCountThisMonth() >= 3 || $this->totalDisciplinePoints() >= 30;
    }
}
