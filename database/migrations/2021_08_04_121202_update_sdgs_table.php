<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSdgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sdgs');

        Schema::create('sdgs', function (Blueprint $table) {
            $table->string('id')->primary(); // not auto-increment to use specific SDG indicator numbers.
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
        Schema::dropIfExists('sdgs');

        Schema::create('sdgs', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // not auto-increment to use specific SDG indicator numbers.
            $table->string('name');
            $table->timestamps();
        });
    }
}