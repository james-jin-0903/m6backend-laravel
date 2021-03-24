<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_image";

    protected $fillable = [
        'app_id', 'image_url'
    ];

    protected $hidden = [
        'deleted_at'
    ];
}
