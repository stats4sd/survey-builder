<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateXlsChoicesRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('xls_choices_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->nullable(); //if null, choice is linked to the 'core', and so the list should be included in all forms.
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
        Schema::dropIfExists('xls_choices_rows');
    }
}
