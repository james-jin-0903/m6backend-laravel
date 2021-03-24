<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkActivityFieldsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('work_activity_fields', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('setting_id');
      $table->unsignedBigInteger('field_id');
      $table->unsignedBigInteger('app_id');
      $table->timestamps();
      $table->softDeletes();

      $table->foreign('setting_id')->references('id')->on('apps_settings')->onDelete('cascade');
      $table->foreign('field_id')->references('id')->on('app_fields')->onDelete('cascade');
      $table->foreign('app_id')->references('id')->on('m6_apps')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('work_activity_fields');
  }
}
