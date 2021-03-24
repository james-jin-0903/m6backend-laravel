<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppPanels extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "panels";

    protected $fillable = [
        'tab_id', 'column', 'weight', 'title', 'description', 'title_pos'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function fields(){
        return $this->hasMany('App\AppFields', 'panel_id', 'id');
    }

    public function tables() {
        return $this->hasMany('App\AppTables', 'panel_id', 'id');
    }
}
