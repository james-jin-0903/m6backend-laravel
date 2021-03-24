<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecordsCount extends Model {
    protected $table = "records_count";

    protected $fillable = [
        'app_id', 'year', 'count'
    ];

    protected $hidden = [ ];
}