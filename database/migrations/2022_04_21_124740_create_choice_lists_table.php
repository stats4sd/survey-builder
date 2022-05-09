<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChoiceListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('choice_lists', function (Blueprint $table) {
            $table->id();
            $table->string('list_name')->unique();
            $table->boolean('is_localisable')->default(0);
            $table->boolean('is_locations')->default(0);
            $table->boolean('is_units')->default(0);
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
        Schema::dropIfExists('choice_lists');
    }
}
