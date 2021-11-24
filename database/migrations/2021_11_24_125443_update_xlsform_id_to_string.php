<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateXlsformIdToString extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xlsforms', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->renameColumn('title', 'id');
            $table->primary('id');
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
            $table->dropColumn('id');
            $table->string('title');
            $table->id();
        });
    }
}
