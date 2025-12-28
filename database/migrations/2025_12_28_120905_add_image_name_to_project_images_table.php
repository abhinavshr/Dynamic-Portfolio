<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('project_images', function (Blueprint $table) {
            $table->string('image_name')->after('project_id');
        });
    }

    public function down(): void {
        Schema::table('project_images', function (Blueprint $table) {
            $table->dropColumn('image_name');
        });
    }
};
