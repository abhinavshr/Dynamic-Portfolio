<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table) {

            // Add category_id column
            $table->foreignId('category_id')
                  ->nullable()
                  ->after('level')
                  ->constrained('categories')
                  ->cascadeOnDelete();

            // Remove old category column
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {

            // Re-add old category column
            $table->string('category')->nullable();

            // Drop foreign key & column
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
