<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppTableRows extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "table_rows";

    protected $fillable = [
        'table_id', 'record_id', 'below_subtotals'
    ];

    protected $hidden = [
        'deleted_at'
    ];

}
