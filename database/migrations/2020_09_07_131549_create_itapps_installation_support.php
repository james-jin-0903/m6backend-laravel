<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsInstallationSupport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_installation_support', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->boolean('firewall_exceptions')->default(false);;
            $table->string('firewall_exceptions_note')->nullable();
            $table->boolean('install_notes')->default(false);;
            $table->string('install_notes_note')->nullable();
            $table->boolean('mapped_drives')->default(false);;
            $table->string('mapped_drives_note')->nullable();
            $table->boolean('registry_changes')->default(false);;
            $table->string('registry_changes_note')->nullable();
            $table->boolean('antivirus_exclusion')->default(false);;
            $table->string('antivirus_exclusion_note')->nullable();
            $table->boolean('ini_changes')->default(false);;
            $table->string('ini_changes_note')->nullable();
            $table->boolean('shortcut_modifications')->default(false);;
            $table->string('shortcut_modifications_note')->nullable();

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
        Schema::dropIfExists('itapps_installation_support');
    }
}
