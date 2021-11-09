<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateModuleIdToModuleVersionIdInXlsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xls_survey_rows', function (Blueprint $table) {
            $table->renameColumn('module_id', 'module_version_id');
        });
        Schema::table('xls_choices_rows', function (Blueprint $table) {
            $table->renameColumn('module_id', 'module_version_id');
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
                $table->renameColumn('module_version_id', 'module_id');
            });
            Schema::table('xls_choices_rows', function (Blueprint $table) {
                $table->renameColumn('module_version_id', 'module_id');
            });
    }
}
