<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldIdToFieldValueRecordReferenced extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('field_value_referenced_record', function (Blueprint $table) {
            $table->unsignedBigInteger('field_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('field_value_referenced_record', function (Blueprint $table) {
            $table->dropColumn('field_id');
        });
    }
}
