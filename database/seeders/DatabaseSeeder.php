<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Pengguna (Admin & Petugas Absensi)
        $admin = User::firstOrCreate(
            ['email' => 'admin@sekolah.sch.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'aktif',
            ]
        );

        $petugas = User::firstOrCreate(
            ['email' => 'petugas@sekolah.sch.id'],
            [
                'name' => 'Petugas Absensi (Budi S.)',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'status' => 'aktif',
            ]
        );

        // 2. Tahun Ajaran
        $tahun2026 = AcademicYear::firstOrCreate(
            ['year_name' => '2026/2027'],
            [
                'start_date' => '2026-07-15',
                'end_date' => '2027-06-25',
                'is_active' => true,
            ]
        );

        AcademicYear::firstOrCreate(
            ['year_name' => '2025/2026'],
            [
                'start_date' => '2025-07-15',
                'end_date' => '2026-06-20',
                'is_active' => false,
            ]
        );

        // 3. Rombongan Belajar (6 Kelas Resmi SMK)
        $c1 = Classroom::firstOrCreate(['name' => 'X-RPL', 'academic_year_id' => $tahun2026->id]);
        $c2 = Classroom::firstOrCreate(['name' => 'X-DKV', 'academic_year_id' => $tahun2026->id]);
        Classroom::firstOrCreate(['name' => 'XI-RPL', 'academic_year_id' => $tahun2026->id]);
        Classroom::firstOrCreate(['name' => 'XI-DKV', 'academic_year_id' => $tahun2026->id]);
        Classroom::firstOrCreate(['name' => 'XII-RPL', 'academic_year_id' => $tahun2026->id]);
        Classroom::firstOrCreate(['name' => 'XII-DKV', 'academic_year_id' => $tahun2026->id]);

        // 4. Data Siswa Contoh untuk Demo
        \App\Models\Student::firstOrCreate(['nis' => '1023401'], [
            'nisn' => '0078129341',
            'name' => 'Ahmad Fauzan Pratama',
            'gender' => 'L',
            'birth_place' => 'Bandung',
            'birth_date' => '2008-05-14',
            'address' => 'Jl. Merdeka No. 12, Bandung',
            'classroom_id' => $c1->id,
            'status' => 'aktif',
        ]);

        \App\Models\Student::firstOrCreate(['nis' => '1023402'], [
            'nisn' => '0078129342',
            'name' => 'Anisa Rahmawati',
            'gender' => 'P',
            'birth_place' => 'Bandung',
            'birth_date' => '2008-08-22',
            'address' => 'Jl. Anggrek No. 5, Bandung',
            'classroom_id' => $c1->id,
            'status' => 'aktif',
        ]);

        \App\Models\Student::firstOrCreate(['nis' => '1023403'], [
            'nisn' => '0078129343',
            'name' => 'Dimas Arya Putra',
            'gender' => 'L',
            'birth_place' => 'Cimahi',
            'birth_date' => '2008-01-10',
            'address' => 'Jl. Raya Barat No. 88, Cimahi',
            'classroom_id' => $c1->id,
            'status' => 'aktif',
        ]);

        \App\Models\Student::firstOrCreate(['nis' => '1023404'], [
            'nisn' => '0078129344',
            'name' => 'Nabila Putri Cahyani',
            'gender' => 'P',
            'birth_place' => 'Bandung',
            'birth_date' => '2008-11-30',
            'address' => 'Jl. Cihampelas No. 40, Bandung',
            'classroom_id' => $c1->id,
            'status' => 'aktif',
        ]);

        \App\Models\Student::firstOrCreate(['nis' => '1023405'], [
            'nisn' => '0078129345',
            'name' => 'Rizky Maulana',
            'gender' => 'L',
            'birth_place' => 'Sumedang',
            'birth_date' => '2008-03-19',
            'address' => 'Jl. Pajajaran No. 17, Bandung',
            'classroom_id' => $c2->id,
            'status' => 'aktif',
        ]);
    }
}
