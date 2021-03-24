<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonomyTerms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxonomy_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vocabulary_id')->unsigned()->nullable();
            $table->foreign('vocabulary_id')->references('id')->on('taxonomy_vocabularies')->onDelete('cascade');
            $table->string('title',255);
            $table->text('description')->nullable();
            $table->tinyInteger('weight');
            $table->unsignedBigInteger('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('taxonomy_terms')->onDelete('cascade');
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
        Schema::dropIfExists('taxonomy_terms');
    }
}
