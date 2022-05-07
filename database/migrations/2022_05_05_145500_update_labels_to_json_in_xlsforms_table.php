<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLabelsToJsonInXlsformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xlsforms', function (Blueprint $table) {
            $table->json('region_label')->change()->nullable();
            $table->json('subregion_level')->change()->nullable();
            $table->json('village_level')->change()->nullable();
            $table->string('location_file')->nullable();
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
            $table->string('region_label')->change()->nullable();
            $table->string('subregion_level')->change()->nullable();
            $table->string('village_level')->change()->nullable();
            $table->dropColumn('location_file');
        });
    }
}
