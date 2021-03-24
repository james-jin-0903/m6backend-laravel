<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_users', function (Blueprint $table) {
          $table->id();
          $table->string('user_id', 255)->nullable();
          $table->string('role', 255)->nullable();
          $table->unsignedBigInteger('feed_id');

          $table->foreign('feed_id')->references('id')->on('generic_feeds')->onDelete('cascade');
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
        Schema::dropIfExists('feed_users');
    }
}
