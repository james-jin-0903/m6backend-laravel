<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsSpecificationMonitoring extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_specification_monitoring', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->text('notes')->nullable();
            $table->string('system_used');
            $table->boolean('dashboard_available');
            $table->string('how_monitored');
            $table->string('connection_protocol_used');

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
        Schema::dropIfExists('itapps_specification_monitoring');
    }
}
