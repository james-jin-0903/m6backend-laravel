<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('panel_id')->nullable();
            $table->unsignedBigInteger('app_id')->nullable();
            $table->tinyInteger('weight');
            $table->string('label',255);
            $table->string('type',255);
            $table->json('metadata');
            $table->foreign('app_id')->references('id')->on('m6_apps')->onDelete('cascade');
            $table->foreign('panel_id')->references('id')->on('panels')->onDelete('cascade');
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
        Schema::dropIfExists('app_fields');
    }
}
