<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsRationalizationCosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_rationalization_costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->float('cost', 18,2)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('cost_category')->unsigned()->nullable();
            $table->unsignedBigInteger('cost_type')->unsigned()->nullable();
            $table->unsignedBigInteger('cost_owner')->unsigned()->nullable();
            $table->unsignedBigInteger('period')->unsigned()->nullable();

            $table->foreign('app_id')->references('id')->on('m6_apps')->onDelete('cascade');
            $table->foreign('period')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('cost_owner')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('cost_type')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('cost_category')->references('id')->on('apps_settings')->onDelete('set null');
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
        Schema::dropIfExists('itapps_rationalization_costs');
    }
}
