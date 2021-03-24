<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsRationalizationLicensing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_rationalization_licensing', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->integer('number_of_licenses')->nullable();
            $table->float('cost_per_license', 18, 2)->nullable();
            $table->float('total_cost', 18, 2)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('purchase_type')->unsigned()->nullable();
            $table->unsignedBigInteger('license_type')->unsigned()->nullable();

            $table->foreign('app_id')->references('id')->on('m6_apps')->onDelete('cascade');
            $table->foreign('license_type')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('purchase_type')->references('id')->on('apps_settings')->onDelete('set null');
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
        Schema::dropIfExists('itapps_rationalization_licensing');
    }
}
