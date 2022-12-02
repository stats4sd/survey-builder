<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReleaseNotesToModuleVersions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_versions', function (Blueprint $table) {
            $table->text('release_notes')->comment('should include any information that module maintainers want end-users to know when reviewing the updates')->nullable();
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
            $table->dropColumn('release_notes');
        });
    }
}
