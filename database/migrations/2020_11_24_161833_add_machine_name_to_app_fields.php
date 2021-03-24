<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMachineNameToAppFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_fields', function (Blueprint $table) {
            $table->string('machine_name', 255)->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if( Schema::hasColumn('app_fields', 'machine_name') ) {
            Schema::table('app_fields', function (Blueprint $table) {
                $table->dropColumn('machine_name');
            });
        }   
    }
}
