<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('panel_id')->nullable();
            $table->unsignedBigInteger('field_id');
            $table->string('permission');

            $table->foreign('app_id')->references('id')->on('m6_apps')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('panel_id')->references('id')->on('panels')->onDelete('cascade');
            $table->foreign('field_id')->references('id')->on('app_fields')->onDelete('cascade');


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
        Schema::dropIfExists('app_roles');
    }
}
