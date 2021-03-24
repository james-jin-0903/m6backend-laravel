<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TagsModel extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_tags";

    protected $fillable = [
        'field', 'value', 'foreign_id'
    ];

    protected $hidden = [
        'deleted_at'
    ];
}
