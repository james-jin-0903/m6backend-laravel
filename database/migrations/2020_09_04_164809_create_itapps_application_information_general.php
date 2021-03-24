<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsApplicationInformationGeneral extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_app_info_general', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id')->required();
            $table->string('vendor_id')->required();
            $table->string('version')->required();
            $table->unsignedBigInteger('status_settings_id')->unsigned()->nullable()->required();
            $table->unsignedBigInteger('first_contact_group_settings_id')->unsigned()->nullable()->required();
            $table->unsignedBigInteger('category_settings_id')->unsigned()->nullable();
            $table->unsignedBigInteger('sub_category_settings_id')->unsigned()->nullable();
            $table->unsignedBigInteger('type_settings_id')->unsigned()->nullable();
            $table->unsignedBigInteger('app_management_settings_id')->unsigned()->nullable();
            $table->unsignedBigInteger('server_hosting_model_settings_id')->unsigned()->nullable();
            $table->unsignedBigInteger('capabilities')->unsigned()->nullable();

            $table->foreign('app_id')->references('id')->on('m6_apps')->onDelete('cascade');
            $table->foreign('status_settings_id')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('first_contact_group_settings_id')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('category_settings_id')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('sub_category_settings_id')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('type_settings_id')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('app_management_settings_id')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('server_hosting_model_settings_id')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('capabilities')->references('id')->on('apps_settings')->onDelete('set null');
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
        Schema::dropIfExists('itapps_app_info_general');
    }
}
