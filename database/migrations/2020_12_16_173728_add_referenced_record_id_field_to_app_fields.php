<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferencedRecordIdFieldToAppFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_fields', function (Blueprint $table) {
            $table->unsignedBigInteger('referenced_record_id')->nullable();
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
            $table->dropColumn('referenced_record_id');
        });
    }
}
