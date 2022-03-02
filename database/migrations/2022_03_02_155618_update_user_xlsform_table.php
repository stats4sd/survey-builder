<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserXlsformTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_xlsform', function (Blueprint $table) {
            $table->string('xlsform_name');
            $table->string('user_id')->change();
            $table->dropColumn('xlsform_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_xlsform', function (Blueprint $table) {
            $table->string('xlsform_id');
            $table->bigInteger('user_id')->change();
            $table->dropColumn('xlsform_name');
        });
    }
}
