<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RationalizationUsers extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_rationalization_users";

    protected $fillable = [
        'app_id', 'users', 'notes', 'user_type'
    ];

    protected $hidden = [
        'deleted_at', 'user_type'
    ];

    public function type(){
        return $this->hasOne('App\AppsSettings','id','user_type')->select('id', 'value', 'field');
    }
}
