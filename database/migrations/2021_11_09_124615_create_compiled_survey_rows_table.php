<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompiledSurveyRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compiled_survey_rows', function (Blueprint $table) {
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
            $table->string('calculation')->nullable();
            $table->string('choice_filter')->nullable();
            $table->boolean('localisable')->default(0);
            $table->json('localise_what')->nullable();
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
        Schema::dropIfExists('compiled_survey_rows');
    }
}
