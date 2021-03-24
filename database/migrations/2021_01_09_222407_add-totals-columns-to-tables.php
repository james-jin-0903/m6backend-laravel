<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalsColumnsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_tables', function (Blueprint $table) {
            $table->boolean('add_total_rows')->default(false);
            $table->boolean('add_sub_totals')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_tables', function (Blueprint $table) {
            //
        });
    }
}
