<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferencedAppToAppFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_fields', function (Blueprint $table) {
          $table->unsignedBigInteger('referenced_app')->nullable();

          $table->foreign('referenced_app')
            ->references('id')
            ->on('m6_apps')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_fields', function (Blueprint $table) {
          $table->dropColumn('referenced_app');
        });
    }
}
