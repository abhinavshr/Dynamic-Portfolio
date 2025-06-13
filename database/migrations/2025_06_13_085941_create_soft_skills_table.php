<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('soft_skills', function (Blueprint $table) {
            $table->id(); // BIGINT Primary Key
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('level')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soft_skills');
    }
};
