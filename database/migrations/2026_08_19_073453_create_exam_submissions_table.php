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
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('file_path')->nullable();
        $table->integer('score')->nullable();
        $table->integer('total_score')->nullable(); // එකතු කරන්න
        $table->integer('max_score')->nullable();   // එකතු කරන්න
        $table->enum('status', ['submitted', 'graded', 'pending'])->default('pending');
        $table->timestamp('submitted_at')->nullable();
        $table->timestamp('graded_at')->nullable();  // එකතු කරන්න
        $table->text('teacher_feedback')->nullable();
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
