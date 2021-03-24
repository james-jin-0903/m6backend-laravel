<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WoAssignments extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "assignments";

    protected $fillable = [
        'work_id', 'assignee', 'status', 'kind'
    ];

    protected $hidden = [
        'deleted_at'
    ];
}
