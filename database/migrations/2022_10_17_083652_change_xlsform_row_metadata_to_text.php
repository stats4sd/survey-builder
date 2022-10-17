<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeXlsformRowMetadataToText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xls_survey_rows', function (Blueprint $table) {
            $table->text('constraint')->nullable()->change();
            $table->text('required')->nullable()->change();
            $table->text('appearance')->nullable()->change();
            $table->text('default')->nullable()->change();
            $table->text('repeat_count')->nullable()->change();
            $table->text('read_only')->nullable()->change();
            $table->text('choice_filter')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('text', function (Blueprint $table) {
            //
        });
    }
}
