<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_rows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('record_id');
            $table->unsignedBigInteger('table_id');
            $table->foreign('record_id')->references('id')->on('app_records')->onDelete('cascade');
            $table->foreign('table_id')->references('id')->on('app_tables')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table_rows', function (Blueprint $table) {
            Schema::dropIfExists('table_rows');
        });
    }
}
