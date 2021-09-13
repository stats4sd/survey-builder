<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id');
            $table->foreignId('core_version_id')->nullable();
            $table->string('version_name');
            $table->boolean('mini')->comment('is this a Reduced / shortened version of a module?')->default(0);
            $table->unsignedInteger('question_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_current')->default(0);
            $table->string('file');
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
        Schema::dropIfExists('module_versions');
    }
}
