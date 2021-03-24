<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RationalizationLicensing extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_rationalization_licensing";

    protected $fillable = [
        'app_id', 'number_of_licenses', 'cost_per_license', 'total_cost', 'notes', 'purchase_type', 'license_type'
    ];

    protected $hidden = [
        'deleted_at', 'license_type', 'purchase_type'
    ];

    public function licenseType(){
        return $this->hasOne('App\AppsSettings','id','license_type')->select('id', 'value', 'field');
    }
    public function purchaseType(){
        return $this->hasOne('App\AppsSettings','id','purchase_type')->select('id', 'value', 'field');
    }
}
