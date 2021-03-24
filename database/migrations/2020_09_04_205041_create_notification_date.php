<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_date', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('recurrence')->nullable();
            // Days
            $table->integer('daily_every_x_day')->nullable();
            $table->integer('daily_every_weekday')->nullable();
            // Weeks
            $table->integer('weekly_recur_every_x_week')->nullable();
            // Months
            $table->integer('monthly_day')->nullable();
            $table->integer('monthly_every_month')->nullable();
            $table->unsignedBigInteger('monthly_ordinal')->unsigned()->nullable();
            $table->unsignedBigInteger('monthly_month')->unsigned()->nullable();
            // Years
            $table->integer('yearly_recur_years')->nullable();
            $table->integer('yearly_day')->nullable();
            $table->unsignedBigInteger('yearly_ordinal')->unsigned()->nullable();
            $table->unsignedBigInteger('yearly_month')->unsigned()->nullable();
            $table->unsignedBigInteger('yearly_week_day')->unsigned()->nullable();
            // Foreign keys
            $table->foreign('yearly_week_day')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('yearly_month')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('yearly_ordinal')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('monthly_month')->references('id')->on('apps_settings')->onDelete('set null');
            $table->foreign('monthly_ordinal')->references('id')->on('apps_settings')->onDelete('set null');
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
        Schema::dropIfExists('notification_date');
    }
}
