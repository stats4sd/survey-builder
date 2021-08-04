<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateXlsformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('xlsforms', function (Blueprint $table) {
            $table->string('xlsfile')->nullable()->change();
            $table->string('status')->default('new')->change();
            $table->timestamp('draft_at')->comment('when did it get first published as a draft')->nullable();
            $table->timestamp('published_at')->comment('when did it go live')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('xlsforms', function (Blueprint $table) {
            $table->string('xlsfile')->change();
            $table->string('status')->nullable()->change();

            $table->dropColumn('draft_at');
            $table->dropColumn('published_at');
        });
    }
}
