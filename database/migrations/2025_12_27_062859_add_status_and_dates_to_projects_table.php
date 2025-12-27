<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndDatesToProjectsTable extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', [
                'active',
                'completed',
                'in_progress',
                'on_hold'
            ])->default('active')->after('github_link');

            $table->date('start_date')->after('status');
            $table->date('end_date')->nullable()->after('start_date');
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['status', 'start_date', 'end_date']);
        });
    }
}
