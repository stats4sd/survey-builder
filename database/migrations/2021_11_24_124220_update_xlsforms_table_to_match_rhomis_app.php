<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateXlsformsTableToMatchRhomisApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xlsforms', function (Blueprint $table) {
            $table->string('name');
            $table->dropColumn('project_id');
            $table->string('project_id');
            $table->renameColumn('odk_central_id', 'centralId');
            $table->boolean('draft')->default(0);
            $table->boolean('complete')->default(0);

            $table->dropColumn('status');
            $table->dropColumn('draft_at');
            $table->dropColumn('published_at');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('xlsforms', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('project_id');
            $table->foreignId('project_id');
            $table->dropForeign('project')->on('projects');
            $table->renameColumn('centralId', 'odk_central_id');
            $table->dropColumn('draft');
            $table->dropColumn('complete');

            $table->string('status')->nullable();
            $table->timestamp('draft_at')->nullable();
            $table->timestamp('published_at')->nullable();
        });
    }
}
