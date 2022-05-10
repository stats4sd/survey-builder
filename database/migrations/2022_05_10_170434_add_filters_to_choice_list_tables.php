<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFiltersToChoiceListTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xlsform_selected_choice_rows', function (Blueprint $table) {
            $table->string('filter')->nullable();
        });

        Schema::table('xls_choices_rows', function (Blueprint $table) {
            $table->string('filter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('xlsform_selected_choice_rows', function (Blueprint $table) {
            $table->dropColumn('filter');
        });

        Schema::table('xls_choices_rows', function (Blueprint $table) {
            $table->dropColumn('filter');
        });
    }
}
