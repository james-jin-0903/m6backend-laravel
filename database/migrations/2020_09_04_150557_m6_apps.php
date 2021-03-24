<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class M6Apps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m6_apps', function (Blueprint $table) {
            $table->id();
            $table->string('app_number', 17)->required();
            $table->string('app_type')->required();
            $table->string('title')->required();
            $table->string('author')->nullable();
            $table->text('description')->required();
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
        Schema::dropIfExists('m6_apps');
    }
}
