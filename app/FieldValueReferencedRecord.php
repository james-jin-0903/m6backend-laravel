<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FieldValueReferencedRecord extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "field_value_referenced_record";

    protected $fillable = [
        'record_id', 'referenced_record_id', 'field_id', 'referenced_field_id'
    ];

    protected $hidden = [
        'deleted_at'
    ];

}
