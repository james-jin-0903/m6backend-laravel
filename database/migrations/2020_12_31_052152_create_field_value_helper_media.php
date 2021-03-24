<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldValueHelperMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_value_helper_media', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('app_id');
          $table->string('helper_media')->nullable();

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
        Schema::dropIfExists('field_value_helper_media');
    }
}
