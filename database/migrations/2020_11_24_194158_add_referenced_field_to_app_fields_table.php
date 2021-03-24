<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferencedFieldToAppFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_fields', function (Blueprint $table) {
            $table->unsignedBigInteger('referenced_field')->nullable();

          $table->foreign('referenced_field')
            ->references('id')
            ->on('app_fields')
            ->onDelete('cascade');
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
          $table->dropColumn('referenced_field');
        });
    }
}
