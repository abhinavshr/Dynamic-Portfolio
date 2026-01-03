<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyLocationToExperiencesTable extends Migration
{
    public function up()
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->string('company_location')->after('company_name');
        });
    }

    public function down()
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn('company_location');
        });
    }
}
