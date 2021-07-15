<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theme_id');
            $table->string('title');
            $table->string('logo')->nullable();
            $table->text('localisation_needs')->nullable();
            $table->text('r_scripts')->nullable();
            $table->json('requires')->comment('list of other modules that this module requires / relies on.')->nullable();
            $table->json('requires_before')->comment('list of other modules that must come before this module in the survey.')->nullable();
            $table->string('version');
            $table->unsignedInteger('minutes');
            $table->boolean('core');
            $table->string('file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
