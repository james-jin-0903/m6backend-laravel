<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppRecords extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "app_records";

    protected $fillable = [
        'app_id', 'record_number', 'image', 'title', 'description', 'status', 'author', 'metadata'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function app() {
        return $this->belongsTo('App\M6Apps', 'app_id');
    }
}
