<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FieldValueReferencedRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_value_referenced_record', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referenced_record_id');
            $table->unsignedBigInteger('record_id');

            $table->foreign('record_id')->references('id')->on('app_records')->onDelete('cascade');
            $table->foreign('referenced_record_id')->references('id')->on('app_records')->onDelete('cascade');
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
        //
    }
}
