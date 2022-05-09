<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHasHouseholdListToXlsformsTable extends Migration
{
    public function up()
    {
        Schema::table('xlsforms', function (Blueprint $table) {
            $table->boolean('has_household_list')->nullable();
        });
    }

    public function down()
    {
        Schema::table('xlsforms', function (Blueprint $table) {
            $table->dropColumn('has_household_list');
        });
    }
}
