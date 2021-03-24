<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\M6Apps;

class AddLayoutTypeToM6Apps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m6_apps', function (Blueprint $table) {
          $table->text('layout_type');
        });
        $m6Apps = M6Apps::all();
        foreach($m6Apps as $app) {
          M6Apps::where('id', $app->id)->update(['layout_type' => 'Profile']);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m6_apps', function (Blueprint $table) {
          $table->dropColumn('layout_type');
        });
    }
}
