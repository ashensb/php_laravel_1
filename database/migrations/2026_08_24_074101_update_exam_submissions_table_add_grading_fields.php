<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_submissions', function (Blueprint $table) {
            $table->integer('total_score')->nullable()->after('score');
            $table->integer('max_score')->nullable()->after('total_score');
            $table->text('teacher_feedback')->nullable()->after('submitted_at');
            $table->timestamp('graded_at')->nullable()->after('teacher_feedback');
        });
    }

    public function down(): void
    {
        Schema::table('exam_submissions', function (Blueprint $table) {
            $table->dropColumn(['total_score', 'max_score', 'teacher_feedback', 'graded_at']);
        });
    }
};