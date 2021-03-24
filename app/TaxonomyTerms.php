<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxonomyTerms extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "taxonomy_terms";

    protected $fillable = [
        'vocabulary_id', 'title', 'description', 'weight', 'parent_id'
    ];

    protected $hidden = [
        'deleted_at'
    ];
}
