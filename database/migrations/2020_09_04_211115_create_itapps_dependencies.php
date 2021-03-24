<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsDependencies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_dependencies', function (Blueprint $table) {
            $table->id();
            $table->string('version');
            $table->text('notes')->nullable();
            $table->boolean('status')->required();
            $table->date('remediation_date');
            $table->unsignedBigInteger('app_id');
            $table->unsignedBigInteger('dependency_type')->unsigned()->nullable()->required();
            $table->unsignedBigInteger('dependency_app_build')->unsigned()->nullable();
            $table->unsignedBigInteger('dependency_eda')->unsigned()->nullable();
            $table->unsignedBigInteger('dependency_update_install_notes')->unsigned()->nullable();
            $table->unsignedBigInteger('dependency_update_exec_path')->unsigned()->nullable();
            $table->unsignedBigInteger('dependency_dct_status')->unsigned()->nullable();
            $table->unsignedBigInteger('dependency_app_compliant')->unsigned()->nullable();

            $table->foreign('dependency_app_compliant')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('dependency_dct_status')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('dependency_update_exec_path')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('dependency_update_install_notes')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('dependency_eda')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('dependency_app_build')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('dependency_type')->references('id')->on('apps_settings')->onDelete('set null');
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
        Schema::dropIfExists('itapps_dependencies');
    }
}
