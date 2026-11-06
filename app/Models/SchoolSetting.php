<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'agency_name',
        'school_address',
        'school_phone',
        'school_website',
        'school_email',
        'city',
        'principal_name',
        'principal_nip',
        'school_logo',
        'entry_time',
        'departure_time',
        'tolerance_minutes',
        'periods_json',
    ];

    /**
     * Ambil pengaturan sekolah tunggal (singleton).
     */
    public static function get(): self
    {
        $setting = self::first();

        if (!$setting) {
            $setting = self::create([
                'school_name' => 'SMK NEGERI REKAYASA PERANGKAT LUNAK',
                'agency_name' => 'DINAS PENDIDIKAN & KEBUDAYAAN',
                'school_address' => 'Jalan Pendidikan No. 45, Kota Pendidikan',
                'school_phone' => '(022) 1234567',
                'school_website' => 'www.smk-rpl.sch.id',
                'school_email' => 'info@smk-rpl.sch.id',
                'city' => 'Kota Pendidikan',
                'principal_name' => 'Drs. H. Mulyadi, M.Pd.',
                'principal_nip' => '19720315 199802 1 002',
                'entry_time' => '07:15',
                'departure_time' => '15:00',
                'tolerance_minutes' => 0,
            ]);
        }

        return $setting;
    }

    /**
     * Ambil struktur jam pelajaran sekolah.
     */
    public function getPeriods(): array
    {
        if (!empty($this->periods_json)) {
            $decoded = json_decode($this->periods_json, true);
            if (is_array($decoded) && count($decoded) > 0) {
                return $decoded;
            }
        }

        return [
            ['period' => 1, 'start' => '07:15', 'end' => '08:00', 'label' => 'Jam Ke-1 (07:15 - 08:00)'],
            ['period' => 2, 'start' => '08:00', 'end' => '08:45', 'label' => 'Jam Ke-2 (08:00 - 08:45)'],
            ['period' => 3, 'start' => '08:45', 'end' => '09:30', 'label' => 'Jam Ke-3 (08:45 - 09:30)'],
            ['period' => 4, 'start' => '09:50', 'end' => '10:35', 'label' => 'Jam Ke-4 (09:50 - 10:35)'],
            ['period' => 5, 'start' => '10:35', 'end' => '11:20', 'label' => 'Jam Ke-5 (10:35 - 11:20)'],
            ['period' => 6, 'start' => '11:20', 'end' => '12:05', 'label' => 'Jam Ke-6 (11:20 - 12:05)'],
            ['period' => 7, 'start' => '12:45', 'end' => '13:30', 'label' => 'Jam Ke-7 (12:45 - 13:30)'],
            ['period' => 8, 'start' => '13:30', 'end' => '14:15', 'label' => 'Jam Ke-8 (13:30 - 14:15)'],
            ['period' => 9, 'start' => '14:15', 'end' => '15:00', 'label' => 'Jam Ke-9 (14:15 - 15:00)'],
        ];
    }
}
