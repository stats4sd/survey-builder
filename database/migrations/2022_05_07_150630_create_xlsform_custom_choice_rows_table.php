<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXlsformCustomChoiceRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xlsform_selected_choice_rows', function (Blueprint $table) {
            $table->id();
            $table->string('xlsform_name');
            $table->foreignId('xls_choices_rows_id')->nullable(); // if this is pulled from the 'default' list, this is the link
            $table->string('list_name');
            $table->string('name');
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
        Schema::dropIfExists('xlsform_custom_choice_rows');
    }
}
