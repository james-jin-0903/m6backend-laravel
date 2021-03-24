<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Configuration extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_configuration";

    protected $fillable = [
        'verified_dependencies', 'static_ip', 'windows_platform', 'web_browser_enabled', 'personal', 'app_admin_rights',
        'other_platform', 'ccow', 'citrix_supported', 'potential_latency_sensitivity', 'dct_application', 'client_server',
        'scw_application', 'vlan_required', 'app_id'
    ];

    protected $hidden = [
        'deleted_at'
    ];
}
