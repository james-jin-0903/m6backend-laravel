<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsRationalizationUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_rationalization_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->integer('users');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('user_type')->unsigned()->nullable();

            $table->foreign('app_id')->references('id')->on('m6_apps')->onDelete('cascade');
            $table->foreign('user_type')->references('id')->on('apps_settings')->onDelete('set null');
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
        Schema::dropIfExists('itapps_rationalization_users');
    }
}
