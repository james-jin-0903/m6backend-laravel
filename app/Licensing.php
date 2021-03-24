<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Licensing extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_licensing";

    protected $fillable = [
        'estimated_users', 'estimated_current_users', 'number_of_licenses', 'details', 'app_id', 'licensing_type'
    ];

    protected $hidden = [
        'deleted_at', 'licensing_type'
    ];

    public function type(){
        return $this->hasOne('App\AppsSettings','id','licensing_type')->select('id', 'value', 'field');
    }
}
