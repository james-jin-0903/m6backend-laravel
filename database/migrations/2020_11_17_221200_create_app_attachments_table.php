<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_attachments', function (Blueprint $table) {
          $table->id();
          $table->string('file_type', 255);
          $table->string('file_extension', 255);
          $table->string('file_name', 255);
          $table->string('file_name_full', 255);
          $table->string('file_path', 255);
          $table->string('file_url', 255);
          $table->integer('file_size');
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
        Schema::dropIfExists('app_attachments');
    }
}
