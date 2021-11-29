<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateXlsformIdToNameInLinkTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('country_xlsform', function (Blueprint $table) {
            $table->dropColumn('xlsform_id');
            $table->string('xlsform_name');
        });

        Schema::table('language_xlsform', function (Blueprint $table) {
            $table->dropColumn('xlsform_id');
            $table->string('xlsform_name');
        });

        Schema::table('module_version_xlsform', function (Blueprint $table) {
            $table->dropColumn('xlsform_id');
            $table->string('xlsform_name');
        });

        Schema::table('theme_xlsform', function (Blueprint $table) {
            $table->dropColumn('xlsform_id');
            $table->string('xlsform_name');
        });

        Schema::table('xlsforms', function(Blueprint $table) {
            $table->dropColumn('id');
            $table->primary('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('country_xlsform', function (Blueprint $table) {
            $table->dropColumn('xlsform_name');
            $table->foreignId('xlsform_id');
        });

        Schema::table('language_xlsform', function (Blueprint $table) {
            $table->dropColumn('xlsform_name');
            $table->foreignId('xlsform_id');
        });

        Schema::table('module_version_xlsform', function (Blueprint $table) {
            $table->dropColumn('xlsform_name');
            $table->foreignId('xlsform_id');
        });

        Schema::table('theme_xlsform', function (Blueprint $table) {
            $table->dropColumn('xlsform_name');
            $table->foreignId('xlsform_id');
        });

        Schema::table('xlsforms', function(Blueprint $table) {
            $table->dropPrimary();
            $table->id();
        });
    }
}
