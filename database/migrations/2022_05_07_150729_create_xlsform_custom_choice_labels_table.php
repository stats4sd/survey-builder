<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXlsformCustomChoiceLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xlsform_selected_choice_labels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('xlsform_selected_choice_row_id');
            $table->foreign('xlsform_selected_choice_row_id', 'selected_choice_to_label')->on('xlsform_selected_choice_rows')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('xlsform_custom_choice_labels');
    }
}
