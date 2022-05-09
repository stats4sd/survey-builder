<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCompiledTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('compiled_choices_labels');
        Schema::dropIfExists('compiled_survey_labels');
        Schema::dropIfExists('compiled_choices_rows');
        Schema::dropIfExists('compiled_survey_rows');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('compiled_survey_rows', function(Blueprint $table) {
            $table->id();
            $table->foreignId('xlsform_id');
            $table->foreignId('module_id');
            $table->string('type');
            $table->string('name');
            $table->string('constraint')->nullable();
            $table->string('required')->nullable();
            $table->string('appearance')->nullable();
            $table->string('default')->nullable();
            $table->string('relevant')->nullable();
            $table->string('repeat_count')->nullable();
            $table->string('read_only')->nullable();
            $table->string('choice_filter')->nullable();
            $table->boolean('localisable')->default(0);
            $table->json('localise_what')->nullable();
            $table->timestamps();
        });

        Schema::create('compiled_survey_labels', function(Blueprint $table) {
            $table->id();
            $table->foreignId('compiled_survey_row_id');
            $table->string('type');
            $table->string('language_id');
            $table->string('label');
            $table->timestamps();
        });

        Schema::create('compiled_choices_rows', function(Blueprint $table){
            $table->id();
            $table->foreignId('xlsform_id');
            $table->foreignId('module_id');
            $table->string('list_name');
            $table->string('name');
            $table->boolean('localisable')->default(0);
            $table->string('list_type')->nullable();
            $table->timestamps();
        });

        Schema::create('compiled_choices_labels', function(Blueprint $table) {
            $table->id();
            $table->foreignId('compiled_choices_row_id');
            $table->string('type');
            $table->string('language_id');
            $table->string('label');
            $table->timestamps();
        });

    }

}
