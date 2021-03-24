<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsConfiguration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_configuration', function (Blueprint $table) {
            $table->id();
            $table->boolean('verified_dependencies')->nullable();
            $table->boolean('static_ip')->nullable();
            $table->boolean('windows_platform')->nullable();
            $table->boolean('web_browser_enabled')->nullable();
            $table->boolean('personal')->nullable();
            $table->boolean('app_admin_rights')->nullable();
            $table->boolean('other_platform')->nullable();
            $table->boolean('ccow')->nullable();
            $table->boolean('citrix_supported')->nullable();
            $table->boolean('potential_latency_sensitivity')->nullable();
            $table->boolean('dct_application')->nullable();
            $table->boolean('client_server')->nullable();
            $table->boolean('scw_application')->nullable();
            $table->boolean('vlan_required')->nullable();
            $table->unsignedBigInteger('app_id');

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
        Schema::dropIfExists('itapps_configuration');
    }
}
