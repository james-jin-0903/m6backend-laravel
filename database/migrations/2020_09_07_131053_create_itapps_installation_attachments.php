<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsInstallationAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_installation_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->string('file_name');
            $table->string('file_url');
            $table->string('attachment');
            $table->text('revision_notes')->nullable();

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
        Schema::dropIfExists('itapps_installation_attachments');
    }
}
