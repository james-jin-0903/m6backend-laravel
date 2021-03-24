<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsRationalizationAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_rationalization_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_id');
            $table->boolean('is_needs');
            $table->text('if_no_need')->nullable();
            $table->float('total_annual_cost', 18, 2);
            $table->integer('estimated_users');
            $table->float('ratio_of_cost_to_user', 18, 2);
            $table->date('retirement_date');
            $table->unsignedBigInteger('rationalization_kind')->unsigned()->nullable();
            $table->unsignedBigInteger('capabilities')->unsigned()->nullable();

            $table->foreign('app_id')->references('id')->on('m6_apps')->onDelete('cascade');
            $table->foreign('capabilities')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('rationalization_kind')->references('id')->on('apps_settings')->onDelete('set null');
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
        Schema::dropIfExists('itapps_rationalization_attributes');
    }
}
