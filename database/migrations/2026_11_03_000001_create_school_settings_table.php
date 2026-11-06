<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->string('school_name')->default('SMK NEGERI REKAYASA PERANGKAT LUNAK');
            $table->string('agency_name')->default('DINAS PENDIDIKAN & KEBUDAYAAN');
            $table->string('school_address')->default('Jalan Pendidikan No. 45, Kota Pendidikan');
            $table->string('school_phone')->default('(022) 1234567');
            $table->string('school_website')->default('www.smk-rpl.sch.id');
            $table->string('school_email')->default('info@smk-rpl.sch.id');
            $table->string('city')->default('Kota Pendidikan');
            $table->string('principal_name')->default('Drs. H. Mulyadi, M.Pd.');
            $table->string('principal_nip')->default('19720315 199802 1 002');
            $table->string('school_logo')->nullable();
            $table->timestamps();
        });

        // Insert default initial settings
        DB::table('school_settings')->insert([
            'school_name' => 'SMK NEGERI REKAYASA PERANGKAT LUNAK',
            'agency_name' => 'DINAS PENDIDIKAN & KEBUDAYAAN',
            'school_address' => 'Jalan Pendidikan No. 45, Kota Pendidikan',
            'school_phone' => '(022) 1234567',
            'school_website' => 'www.smk-rpl.sch.id',
            'school_email' => 'info@smk-rpl.sch.id',
            'city' => 'Kota Pendidikan',
            'principal_name' => 'Drs. H. Mulyadi, M.Pd.',
            'principal_nip' => '19720315 199802 1 002',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};
