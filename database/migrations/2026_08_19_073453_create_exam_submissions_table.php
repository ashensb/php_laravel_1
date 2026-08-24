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
    Schema::create('exam_submissions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('exam_id')->constrained()->onDelete('cascade');
        $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
        $table->json('answers')->nullable();
        $table->string('file_path')->nullable();
        $table->integer('score')->nullable();
        $table->integer('total_score')->nullable();
        $table->integer('max_score')->nullable();
        $table->enum('status', ['submitted', 'graded', 'pending'])->default('pending');
        $table->timestamp('submitted_at')->nullable();
        $table->text('teacher_feedback')->nullable();
        $table->text('feedback')->nullable();
        $table->integer('marks_obtained')->nullable();
        $table->timestamp('graded_at')->nullable();
        $table->timestamps();
    });
   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_submissions');
    }
};
