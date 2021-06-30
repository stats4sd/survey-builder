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
            $table->string('logo');
            $table->text('localisation_needs');
            $table->text('r_scripts');
            $table->text('ordering_rules');
            $table->string('version');
            $table->unsignedInteger('minutes');
            $table->boolean('core');
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
