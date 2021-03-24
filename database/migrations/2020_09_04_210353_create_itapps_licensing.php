<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsLicensing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_licensing', function (Blueprint $table) {
            $table->id();
            $table->integer('estimated_users');
            $table->integer('estimated_current_users');
            $table->integer('number_of_licenses');
            $table->string('details');
            $table->unsignedBigInteger('app_id')->unsigned()->nullable();
            $table->unsignedBigInteger('licensing_type')->unsigned()->nullable();

            $table->foreign('licensing_type')->references('id')->on('apps_settings')->onDelete('set null');
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
        Schema::dropIfExists('itapps_licensing');
    }
}
