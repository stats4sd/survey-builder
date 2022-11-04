<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTestStatusToModuleVersionsField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_versions', function (Blueprint $table) {
            $table->boolean('test_success')->default(0);
            $table->boolean('test_failed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('module_versions', function (Blueprint $table) {
            $table->dropColumn('test_success');
            $table->dropColumn('test_failed');
        });
    }
}
