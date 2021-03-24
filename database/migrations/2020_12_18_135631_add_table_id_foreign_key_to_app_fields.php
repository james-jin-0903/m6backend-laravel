<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableIdForeignKeyToAppFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_fields', function (Blueprint $table) {
            $table->unsignedBigInteger('table_id')->nullable();
            $table->foreign('table_id')->references('id')->on('app_tables')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if( Schema::hasColumn('app_fields', 'table_id') ) {
            Schema::table('app_fields', function (Blueprint $table) {
                $table->dropColumn('table_id');
            });
        }
    }
}
