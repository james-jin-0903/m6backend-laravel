<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStandardFieldsToAppRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_records', function (Blueprint $table) {
            $table->string('class', 255)->nullable();
            $table->string('category', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->string('state', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $columns = [ 'class', 'category', 'type', 'state' ];

        foreach ($columns as $key => $col) {
            if( Schema::hasColumn('app_records', $col) ) {
                Schema::table('app_records', function (Blueprint $table) {
                    $table->dropColumn($col);
                });
            }
        }
    }
}
