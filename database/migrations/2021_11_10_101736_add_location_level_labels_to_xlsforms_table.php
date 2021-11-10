<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationLevelLabelsToXlsformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xlsforms', function (Blueprint $table) {
            $table->string('region_label')->nullable();
            $table->string('subregion_level')->nullable();
            $table->string('village_level')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('xlsforms', function (Blueprint $table) {
            $table->dropColumn('region_label');
            $table->dropColumn('subregion_level');
            $table->dropColumn('village_level');
        });
    }
}
