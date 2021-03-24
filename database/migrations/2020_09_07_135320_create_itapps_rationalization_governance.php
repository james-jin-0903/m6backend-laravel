<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsRationalizationGovernance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_rationalization_governance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->string('primary_customer_group');
            $table->string('estimated_days_to_replace');
            $table->unsignedBigInteger('responsible_division')->unsigned()->nullable();
            $table->unsignedBigInteger('responsible_committee')->unsigned()->nullable();
            $table->string('responsible_manager');
            $table->string('responsible_vp_dir');
            $table->unsignedBigInteger('first_contact_group')->unsigned()->nullable();

            $table->foreign('app_id')->references('id')->on('m6_apps')->onDelete('cascade');
            $table->foreign('first_contact_group')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('responsible_committee')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('responsible_division')->references('id')->on('apps_settings')->onDelete('set null');
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
        Schema::dropIfExists('itapps_rationalization_governance');
    }
}
