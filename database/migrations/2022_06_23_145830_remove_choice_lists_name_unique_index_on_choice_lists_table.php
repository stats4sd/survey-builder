<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveChoiceListsNameUniqueIndexOnChoiceListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xls_survey_rows', function(Blueprint $table) {
            $table->dropForeign('xls_survey_rows_choice_list_foreign');
        });

        Schema::table('choice_lists', function(Blueprint $table) {
            $table->dropIndex('choice_lists_list_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
