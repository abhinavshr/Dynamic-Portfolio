<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->boolean('is_current')
                  ->default(false)
                  ->after('end_date');
        });
    }

    public function down()
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn('is_current');
        });
    }
};
