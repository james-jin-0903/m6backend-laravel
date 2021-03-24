<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppTabs extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "tabs";

    protected $fillable = [
        'app_id', 'weight', 'title', 'order', 'readOnly', 'full_width'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function app(){
        return $this->belongsTo('App\M6Apps', 'id', 'app_id');
    }

    public function panels(){
        return $this->hasMany('App\AppPanels', 'tab_id', 'id');
    }

}
