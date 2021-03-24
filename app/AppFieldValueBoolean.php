<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppFieldValueBoolean extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "field_value_boolean";

    protected $fillable = [
        'record_id', 'field_id', 'value', 'table_row_id'
    ];

    protected $hidden = [
        'deleted_at'
    ];

}
