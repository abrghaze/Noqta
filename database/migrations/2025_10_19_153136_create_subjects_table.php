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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Mathematics", "Physics", "French"
            $table->foreignId('class_id')->constrained()->onDelete('cascade'); // Link to classes table
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null'); // Teacher for this subject
            $table->integer('coefficient')->default(1); // Subject weight for grade calculation
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
