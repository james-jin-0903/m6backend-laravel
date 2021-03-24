<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsInstallationGenerals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_installation_generals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->integer('priority');
            $table->boolean('odbc_connection_required');
            $table->string('odbc_contact_name');
            $table->string('path_to_executable');
            $table->string('odbc_settings');
            $table->string('general_notes')->nullable();
            $table->unsignedBigInteger('install_type')->unsigned()->nullable();
            $table->unsignedBigInteger('delivery_method')->unsigned()->nullable();
            $table->unsignedBigInteger('windows_passed_dct')->unsigned()->nullable();
            $table->unsignedBigInteger('ldap_ad_authentication')->unsigned()->nullable();

            $table->foreign('app_id')->references('id')->on('m6_apps')->onDelete('cascade');
            $table->foreign('ldap_ad_authentication')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('windows_passed_dct')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('delivery_method')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('install_type')->references('id')->on('apps_settings')->onDelete('set null');
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
        Schema::dropIfExists('itapps_installation_generals');
    }
}
