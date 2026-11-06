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
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained('classrooms')->onDelete('restrict');
            $table->date('attendance_date');
            $table->string('session_name')->default('Pagi');
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();

            // Constraint bisnis BR-04 & PRD: Mencegah sesi ganda pada kelas, tanggal, dan sesi yang sama
            $table->unique(['classroom_id', 'attendance_date', 'session_name'], 'unique_session_per_class_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_sessions');
    }
};
