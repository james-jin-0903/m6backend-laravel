<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarketplaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id')->required();
            $table->string('status', 255);
            $table->longText('overview');

            $table->foreign('app_id')
              ->references('id')
              ->on('m6_apps')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marketplace');
    }
}
