<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RationalizationAttributes extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_rationalization_attributes";

    protected $fillable = [
        'app_id', 'is_needs', 'if_no_need', 'total_annual_cost', 'estimated_users', 'ratio_of_cost_to_user',
        'application_value_tag_key', 'retirement_date', 'rationalization_kind', 'capabilities'
    ];

    protected $hidden = [
        'deleted_at', 'capabilities', 'rationalization_kind'
    ];

    public function capability(){
        return $this->hasOne('App\AppsSettings','id','capabilities')->select('id', 'value', 'field');
    }
    public function rationalizationKind(){
        return $this->hasOne('App\AppsSettings','id','rationalization_kind')->select('id', 'value', 'field');
    }

    public function applicationValue(){
        return $this->hasMany('App\TagsModel', 'foreign_id', 'app_id')->
            where('field', 'ratio_attributes')->orderByRaw('id');
    }
}
