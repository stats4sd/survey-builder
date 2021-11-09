<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocalisableColumnToXlsChoicesRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xls_choices_rows', function (Blueprint $table) {
            $table->boolean('localisable')->default(0);
            $table->string('list_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('xls_choices_rows', function (Blueprint $table) {
            $table->dropColumn('localisable');
            $table->string('list_type')->nullable();
        });
    }
}
