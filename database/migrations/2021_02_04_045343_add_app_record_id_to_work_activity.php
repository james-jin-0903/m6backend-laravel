<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAppRecordIdToWorkActivity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_activity', function (Blueprint $table) {
          $table->unsignedBigInteger('app_record_id')->nullable();
          $table->foreign('app_record_id')->references('id')->on('app_records')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_activity', function (Blueprint $table) {
          $table->dropColumn('app_record_id');
        });
    }
}
