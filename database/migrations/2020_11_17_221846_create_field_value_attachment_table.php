<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldValueAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_value_attachment', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('record_id');
          $table->unsignedBigInteger('field_id');
          $table->unsignedBigInteger('value');

          $table->foreign('record_id')->references('id')->on('app_records')->onDelete('cascade');
          $table->foreign('field_id')->references('id')->on('app_fields')->onDelete('cascade');
          $table->foreign('value')->references('id')->on('app_attachments')->onDelete('cascade');
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
        Schema::dropIfExists('field_value_attachment');
    }
}
