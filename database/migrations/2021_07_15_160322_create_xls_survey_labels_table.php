<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXlsSurveyLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xls_survey_labels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('xls_survey_row_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->string('language_id');
            $table->string('label');


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
        Schema::dropIfExists('xls_survey_labels');
    }
}
