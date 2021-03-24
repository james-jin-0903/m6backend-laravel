<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RationalizationFte extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_rationalization_fte";

    protected $fillable = [
        'app_id', 'fte_count', 'fte_costs', 'notes', 'fte_type'
    ];

    protected $hidden = [
        'deleted_at', 'fte_type'
    ];

    public function type(){
        return $this->hasOne('App\AppsSettings','id','fte_type')->select('id', 'value', 'field');
    }
}
