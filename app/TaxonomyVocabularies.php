<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxonomyVocabularies extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "taxonomy_vocabularies";

    protected $fillable = [
        'title', 'description', 'weight'
    ];

    protected $hidden = [
        'deleted_at'
    ];
}
