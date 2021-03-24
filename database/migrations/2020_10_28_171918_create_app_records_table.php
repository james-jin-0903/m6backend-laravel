<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->unsignedBigInteger('panel_id')->nullable();
            $table->string('record_number',255);
            $table->string('title',255);
            $table->string('description',255);
            $table->string('status',255);
            $table->string('author',255);
            $table->json('metadata');

            $table->foreign('panel_id')->references('id')->on('panels')->onDelete('cascade');
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
        Schema::dropIfExists('app_records');
    }
}
