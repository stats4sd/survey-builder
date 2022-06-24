<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLinksToChoiceListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // survey
        Schema::table('xls_survey_rows', function (Blueprint $table) {
            $table->dropIndex('xls_survey_rows_choice_list_foreign');
            $table->foreignId('choice_list_id')->nullable();
        });

        Schema::table('xls_choices_rows', function(Blueprint $table) {
            $table->foreignId('choice_list_id');
            // TODO: decide if we should remove module_version_id and link module_version_id only via choice_lists.
        });

        Schema::table('xlsform_selected_choice_rows', function (Blueprint $table) {
            $table->foreignId('choice_list_id');
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
            $table->index('choice_list', 'xls_survey_rows_choice_list_foreign');
            $table->dropColumn('choice_list_id');
        });

        Schema::table('xls_choices_rows', function(Blueprint $table) {
            $table->dropColumn('choice_list_id');
        });

        Schema::table('xlsform_selected_choice_rows', function (Blueprint $table) {
            $table->dropColumn('choice_list_id');
        });
    }
}
