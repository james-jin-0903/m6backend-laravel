<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppFieldValues extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "app_fields_values";

    protected $fillable = [
        'record_id', 'field_id', 'value'
    ];

    protected $hidden = [
        'deleted_at'
    ];

}
