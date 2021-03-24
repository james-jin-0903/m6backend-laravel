<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppFieldValueHelperMedia extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "field_value_helper_media";

    protected $fillable = [
        'app_id', 'helper_media'
    ];

    protected $hidden = [
        'deleted_at'
    ];

}
