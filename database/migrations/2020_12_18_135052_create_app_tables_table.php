<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_tables', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->unsignedBigInteger('app_id');
            $table->unsignedBigInteger('panel_id');
            $table->foreign('app_id')->references('id')->on('m6_apps')->onDelete('cascade');
            $table->foreign('panel_id')->references('id')->on('panels')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('app_tables');
    }
}
