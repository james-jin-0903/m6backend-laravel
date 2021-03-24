<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->tinyInteger('weight');
            $table->string('title',255);
            $table->foreign('app_id')->references('id')->on('m6_apps')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tabs');
    }
}
