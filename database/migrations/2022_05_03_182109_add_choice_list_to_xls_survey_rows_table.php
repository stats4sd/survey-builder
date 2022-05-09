<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChoiceListToXlsSurveyRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xls_survey_rows', function (Blueprint $table) {
            $table->string('choice_list')->nullable();
            $table->foreign('choice_list')->references('list_name')->on('choice_lists')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('xls_survey_rows', function (Blueprint $table) {
            $table->dropForeign('choice_list');
            $table->dropColumn('choice_list');
        });
    }
}
