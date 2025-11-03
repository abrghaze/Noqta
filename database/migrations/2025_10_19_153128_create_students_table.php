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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to users table
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('set null'); // Link to classes table
            $table->foreignId('parent_id')->nullable()->constrained('parents')->onDelete('set null'); // Link to parents table
            $table->string('matricule')->unique(); // Student ID number
            $table->date('date_of_birth')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
