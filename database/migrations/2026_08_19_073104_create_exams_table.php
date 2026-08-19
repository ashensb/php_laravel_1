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
    Schema::create('exams', function (Blueprint $table) {
        $table->id();
        $table->foreignId('course_subject_id')->constrained()->onDelete('cascade');
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Teacher ID
        $table->string('title');
        $table->text('instructions')->nullable();
        $table->enum('type', ['mcq', 'assignment']);
        $table->dateTime('start_time')->nullable();
        $table->dateTime('end_time')->nullable();
        $table->integer('total_marks')->default(100);
        $table->boolean('is_published')->default(false);
        $table->timestamps();
    });
   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
