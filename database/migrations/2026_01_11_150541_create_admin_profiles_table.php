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
        Schema::create('admin_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('phone_number')->nullable();
            $table->string('professional_title')->nullable();
            $table->string('tagline')->nullable();

            // Long About Me
            $table->longText('about_me')->nullable();

            // Portfolio Stats
            $table->integer('years_of_experience')->nullable();
            $table->integer('projects_completed')->nullable();
            $table->integer('happy_clients')->nullable();
            $table->integer('technologies_used')->nullable();

            // Social / Professional Links
            $table->string('github_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('cv_url')->nullable();
            $table->string('twitter_url')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_profiles');
    }
};
