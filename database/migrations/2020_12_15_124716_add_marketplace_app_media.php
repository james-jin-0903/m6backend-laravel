<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarketplaceAppMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketplace_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id')->required();
            $table->unsignedBigInteger('file_id')->required();

            $table->foreign('app_id')
              ->references('id')
              ->on('marketplace')
              ->onDelete('cascade');

            $table->foreign('file_id')
              ->references('id')
              ->on('app_attachments')
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
        Schema::dropIfExists('marketplace_media');
    }
}
