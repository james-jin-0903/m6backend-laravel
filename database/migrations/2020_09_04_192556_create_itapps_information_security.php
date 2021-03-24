<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsInformationSecurity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_information_security', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id')->required();
            $table->boolean('facing')->required();
            $table->boolean('phi')->required();
            $table->boolean('pci')->required();
            $table->unsignedBigInteger('ssn')->unsigned()->nullable()->required();

            $table->foreign('app_id')->references('id')->on('m6_apps')->onDelete('cascade');
            $table->foreign('ssn')->references('id')->on('apps_settings')->onDelete('set null');
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
        Schema::dropIfExists('itapps_information_security');
    }
}
