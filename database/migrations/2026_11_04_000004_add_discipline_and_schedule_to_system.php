<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambahkan jadwal bel dan jam masuk ke tabel school_settings
        Schema::table('school_settings', function (Blueprint $table) {
            $table->string('entry_time', 10)->default('07:15')->after('city');
            $table->string('departure_time', 10)->default('15:00')->after('entry_time');
            $table->integer('tolerance_minutes')->default(0)->after('departure_time');
            $table->text('periods_json')->nullable()->after('tolerance_minutes');
        });

        // 2. Tambahkan kontak orang tua / wali ke tabel students
        Schema::table('students', function (Blueprint $table) {
            $table->string('parent_name', 150)->nullable()->after('address');
            $table->string('parent_phone', 30)->nullable()->after('parent_name');
        });

        // 3. Tambahkan kolom kedisiplinan dan jam pelajaran terlewat ke tabel attendances
        Schema::table('attendances', function (Blueprint $table) {
            $table->integer('late_minutes')->default(0)->after('status');
            $table->string('missed_periods', 255)->nullable()->after('late_minutes');
            $table->integer('discipline_points')->default(0)->after('missed_periods');
            $table->string('compensation_task', 255)->nullable()->after('discipline_points');
            $table->string('compensation_status', 20)->default('pending')->after('compensation_task'); // 'pending', 'completed'
            $table->timestamp('tap_out_at')->nullable()->after('compensation_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn([
                'late_minutes',
                'missed_periods',
                'discipline_points',
                'compensation_task',
                'compensation_status',
                'tap_out_at',
            ]);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['parent_name', 'parent_phone']);
        });

        Schema::table('school_settings', function (Blueprint $table) {
            $table->dropColumn([
                'entry_time',
                'departure_time',
                'tolerance_minutes',
                'periods_json',
            ]);
        });
    }
};
