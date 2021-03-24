<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableRowIdToFieldValueNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('field_value_number', function (Blueprint $table) {
            $table->unsignedBigInteger('table_row_id')->nullable();
            $table->foreign('table_row_id')->references('id')->on('table_rows')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('field_value_number', function (Blueprint $table) {
            $table->dropColumn('table_row_id');
        });
    }
}
