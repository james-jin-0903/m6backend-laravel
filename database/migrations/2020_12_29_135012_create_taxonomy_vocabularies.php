<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonomyVocabularies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxonomy_vocabularies', function (Blueprint $table) {
            $table->id();
            $table->string('title',255);
            $table->text('description')->nullable();
            $table->tinyInteger('weight');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxonomy_vocabularies');
    }
}
