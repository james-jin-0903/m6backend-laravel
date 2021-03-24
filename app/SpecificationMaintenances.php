<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpecificationMaintenances extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_specification_maintenances";

    protected $fillable = [
        'app_id', 'installation_date', 'installed_by', 'set_for_refresh', 'last_login', 'last_reboot', 'refresh_date',
        'os_service_pack', 'future_os_service_pack', 'patching_method', 'patching_responsibility', 'recovery',
        'patching_notes', 'ip_address', 'switch_ip_address', 'network_connection', 'mac_address',
        'minimum_memory_required', 'typical_memory_usage', 'minimum_disc_space_required', 'network_notes',
        'operating_system', 'future_op_system', 'ip_address_type', 'network_zone_type'
    ];

    protected $hidden = [
        'deleted_at', 'network_zone_type', 'ip_address_type', 'future_op_system', 'operating_system'
    ];

    public function networkZoneType(){
        return $this->hasOne('App\AppsSettings','id','network_zone_type')->select('id', 'value', 'field');
    }
    public function ipAddressType(){
        return $this->hasOne('App\AppsSettings','id','ip_address_type')->select('id', 'value', 'field');
    }
    public function futureOpSystem(){
        return $this->hasOne('App\AppsSettings','id','future_op_system')->select('id', 'value', 'field');
    }
    public function operatingSystem(){
        return $this->hasOne('App\AppsSettings','id','operating_system')->select('id', 'value', 'field');
    }
}
