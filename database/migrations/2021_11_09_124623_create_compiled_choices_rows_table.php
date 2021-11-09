<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompiledChoicesRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compiled_choices_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('xlsform_id');
            $table->foreignId('module_id')->nullable(); //if null, choice is linked to the 'core', and so the list should be included in all forms.
            $table->string('list_name');
            $table->string('name');
            $table->boolean('localisable');
            $table->string('list_type');

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
        Schema::dropIfExists('compiled_choices_rows');
    }
}
