<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RationalizationCosts extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_rationalization_costs";

    protected $fillable = [
        'app_id', 'cost', 'notes', 'cost_category', 'cost_type', 'cost_owner', 'period'
    ];

    protected $hidden = [
        'deleted_at', 'period', 'cost_owner', 'cost_type', 'cost_category'
    ];

    public function getPeriod(){
        return $this->hasOne('App\AppsSettings','id','period')->select('id', 'value', 'field');
    }
    public function owner(){
        return $this->hasOne('App\AppsSettings','id','cost_owner')->select('id', 'value', 'field');
    }
    public function type(){
        return $this->hasOne('App\AppsSettings','id','cost_type')->select('id', 'value', 'field');
    }
    public function category(){
        return $this->hasOne('App\AppsSettings','id','cost_category')->select('id', 'value', 'field');
    }
}
