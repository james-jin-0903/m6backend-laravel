<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_notification', function (Blueprint $table) {
            $table->id();
            $table->string('contact_id');
            $table->unsignedBigInteger('notification_id')->unsigned()->nullable();

            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('set null');
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
        Schema::dropIfExists('contact_notification');
    }
}
