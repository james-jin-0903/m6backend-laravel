<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsRationalizationFte extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_rationalization_fte', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->integer('fte_count')->nullable();
            $table->float('fte_costs', 18, 2)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('fte_type')->unsigned()->nullable();

            $table->foreign('app_id')->references('id')->on('m6_apps')->onDelete('cascade');
            $table->foreign('fte_type')->references('id')->on('apps_settings')->onDelete('set null');
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
        Schema::dropIfExists('itapps_rationalization_fte');
    }
}
