<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXlsChoicesLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xls_choices_labels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('xls_choices_rows');
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
        Schema::dropIfExists('xls_choices_labels');
    }
}
