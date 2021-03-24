<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InformationSecurity extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_information_security";

    protected $fillable = [
        'app_id', 'facing', 'phi', 'pci', 'ssn'
    ];

    protected $hidden = [
        'deleted_at', 'ssn'
    ];

    public function ssnForeign(){
        return $this->hasOne('App\AppsSettings','id','ssn')->select('id', 'value', 'field');
    }
}
