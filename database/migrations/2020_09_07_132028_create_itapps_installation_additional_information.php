<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsInstallationAdditionalInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_installation_additional_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->string('previous_software_version');
            $table->string('groups_machine');
            $table->string('groups_user');

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
        Schema::dropIfExists('itapps_installation_additional_information');
    }
}
