<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpecificationMonitoring extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_specification_monitoring";

    protected $fillable = [
        'app_id', 'notes', 'system_used', 'dashboard_available', 'how_monitored', 'connection_protocol_used'
    ];

    protected $hidden = [
        'deleted_at'
    ];
}
