<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstallationGenerals extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_installation_generals";

    protected $fillable = [
        'app_id', 'priority', 'odbc_connection_required', 'odbc_contact_name', 'path_to_executable', 'odbc_settings',
        'general_notes', 'install_type', 'delivery_method', 'windows_passed_dct', 'ldap_ad_authentication'
    ];

    protected $hidden = [
        'deleted_at', 'ldap_ad_authentication', 'windows_passed_dct', 'delivery_method', 'install_type'
    ];

    public function ldapAdAuthentication(){
        return $this->hasOne('App\AppsSettings','id','ldap_ad_authentication')->select('id', 'value', 'field');
    }

    public function windowsPassedDct(){
        return $this->hasOne('App\AppsSettings','id','windows_passed_dct')->select('id', 'value', 'field');
    }

    public function deliveryMethod(){
        return $this->hasOne('App\AppsSettings','id','delivery_method')->select('id', 'value', 'field');
    }

    public function installType(){
        return $this->hasOne('App\AppsSettings','id','install_type')->select('id', 'value', 'field');
    }
}
