<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsSpecificationMaintenances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_specification_maintenances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->date('installation_date');
            $table->string('installed_by');
            $table->date('set_for_refresh');
            $table->date('last_login');
            $table->date('last_reboot');
            $table->date('refresh_date');
            $table->string('os_service_pack');
            $table->string('future_os_service_pack');
            $table->string('patching_method');
            $table->string('patching_responsibility');
            $table->string('recovery');
            $table->text('patching_notes')->nullable();
            $table->string('ip_address');
            $table->string('switch_ip_address');
            $table->string('network_connection');
            $table->string('mac_address');
            $table->string('minimum_memory_required');
            $table->string('typical_memory_usage');
            $table->string('minimum_disc_space_required');
            $table->text('network_notes')->nullable();
            $table->unsignedBigInteger('operating_system')->unsigned()->nullable();
            $table->unsignedBigInteger('future_op_system')->unsigned()->nullable();
            $table->unsignedBigInteger('ip_address_type')->unsigned()->nullable();
            $table->unsignedBigInteger('network_zone_type')->unsigned()->nullable();

            $table->foreign('app_id')->references('id')->on('m6_apps')->onDelete('cascade');
            $table->foreign('network_zone_type')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('ip_address_type')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('future_op_system')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('operating_system')->references('id')->on('apps_settings')->onDelete('set null');
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
        Schema::dropIfExists('itapps_specification_maintenances');
    }
}
