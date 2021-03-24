<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_activity', function (Blueprint $table) {
            $table->id();
            $table->string('activity_number');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('author');
            $table->unsignedBigInteger('application_id')->unsigned()->nullable();
            $table->unsignedBigInteger('record_id')->unsigned()->nullable();
            $table->string('status');
            $table->unsignedBigInteger('type')->unsigned()->nullable();
            $table->date('requested_date');
            $table->date('start_date');
            $table->date('due_date');
            $table->date('end_date')->nullable();
            $table->string('post_id');

            $table->foreign('application_id')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('record_id')->references('id')->on('m6_apps')->onDelete('cascade');
            $table->foreign('type')->references('id')->on('apps_settings')->onDelete('set null');
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
        Schema::dropIfExists('work_activity');
    }
}
