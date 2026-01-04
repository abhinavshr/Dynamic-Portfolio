<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            // Remove certificate photo
            if (Schema::hasColumn('certificates', 'certificate_photo')) {
                $table->dropColumn('certificate_photo');
            }

            // Add new fields
            $table->string('credential_id')->nullable()->after('issue_date');
            $table->string('verification_url')->nullable()->after('credential_id');
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            // Re-add removed column
            $table->string('certificate_photo')->nullable();

            // Remove added columns
            $table->dropColumn(['credential_id', 'verification_url']);
        });
    }
};
