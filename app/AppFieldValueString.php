<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppFieldValueString extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "field_value_string";

    protected $fillable = [
        'record_id', 'field_id', 'value', 'table_row_id'
    ];

    protected $hidden = [
        'deleted_at'
    ];

}
