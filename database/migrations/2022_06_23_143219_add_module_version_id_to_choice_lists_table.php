<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModuleVersionIdToChoiceListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('choice_lists', function (Blueprint $table) {
            $table->foreignId('module_version_id')->after('list_name'); // references module_versions table
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('choice_lists', function (Blueprint $table) {
            $table->dropColumn('module_version_id');
        });
    }
}
