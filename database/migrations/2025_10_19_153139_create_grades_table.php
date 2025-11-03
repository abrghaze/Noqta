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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade'); // Link to students table
            $table->foreignId('subject_id')->constrained()->onDelete('cascade'); // Link to subjects table
            $table->decimal('grade_value', 5, 2); // Grade value (e.g., 15.50 out of 20)
            $table->decimal('max_grade', 5, 2)->default(20); // Maximum possible grade
            $table->string('exam_type')->nullable(); // e.g., "Quiz", "Midterm", "Final"
            $table->date('date'); // Date of the exam
            $table->text('comment')->nullable(); // Teacher's comment
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
