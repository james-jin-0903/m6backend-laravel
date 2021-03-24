<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpecificationCertifications extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_specification_certifications";

    protected $fillable = [
        'app_id', 'name', 'time_to_certified', 'required', 'compliant', 'certified', 'start_date', 'expiration_date',
        'first_certificated', 'maint_certi_type'
    ];

    protected $hidden = [
        'deleted_at', 'maint_certi_type'
    ];

    public function type(){
        return $this->hasOne('App\AppsSettings','id','maint_certi_type')->select('id', 'value', 'field');
    }
}
