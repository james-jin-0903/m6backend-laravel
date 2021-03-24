<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItappsContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itapps_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_name');
            $table->string('number');
            $table->boolean('status');
            $table->date('start_contract')->nullable();
            $table->date('finish_contract')->nullable();
            $table->integer('term_length')->unsigned()->nullable();
            $table->string('capped_inflator')->nullable();
            $table->date('critical_decision_date')->nullable();
            $table->string('capped_inflator_value')->nullable();
            $table->unsignedBigInteger('app_id');
            $table->unsignedBigInteger('term_until')->unsigned()->nullable();
            $table->integer('term_notice_period')->unsigned()->nullable();

            $table->foreign('term_until')->references('id')->on('apps_settings')->onDelete('set null');
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
        Schema::dropIfExists('itapps_contracts');
    }
}
