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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade'); // Link to students table
            $table->foreignId('subject_id')->constrained()->onDelete('cascade'); // Link to subjects table
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present'); // Attendance status
            $table->date('date'); // Date of attendance
            $table->text('reason')->nullable(); // Reason for absence if applicable
            $table->timestamps();
            
            // Ensure unique attendance record per student per subject per date
            $table->unique(['student_id', 'subject_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
