<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToWorkActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_activity', function (Blueprint $table) {
          $table->string('appointment_time')->nullable();
          $table->string('father_post_id')->nullable();
          $table->string('meeting_time')->nullable();
          $table->string('location')->nullable();
          $table->string('colors', 60)->nullable();
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
          $table->dropColumn('appointment_time');
          $table->dropColumn('meeting_time');
          $table->dropColumn('location');
          $table->dropColumn('colors');
        });
    }
}
