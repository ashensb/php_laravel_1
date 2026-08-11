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
    Schema::table('students', function (Blueprint $table) {
        // Add foreign key for batch_id (nullable so existing students don't break)
        $table->foreignId('batch_id')->nullable()->constrained('batches')->onDelete('set null');
    });
   }

   public function down(): void
   {
    Schema::table('students', function (Blueprint $table) {
        $table->dropForeign(['batch_id']);
        $table->dropColumn('batch_id');
    });
   }
};
