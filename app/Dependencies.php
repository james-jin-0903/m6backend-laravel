<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dependencies extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_dependencies";

    protected $fillable = [
        'version', 'notes', 'status', 'remediation_date', 'app_id', 'dependency_type', 'dependency_app_build',
        'dependency_eda', 'dependency_update_install_notes', 'dependency_dct_status', 'dependency_app_compliant',
        'dependency_update_exec_path'
    ];

    protected $hidden = [
        'deleted_at', 'dependency_app_compliant', 'dependency_dct_status', 'dependency_update_install_notes',
        'dependency_eda', 'dependency_app_build', 'dependency_type', 'dependency_update_exec_path'
    ];

    public function appCompliant(){
        return $this->hasOne('App\AppsSettings','id','dependency_app_compliant')->select('id', 'value', 'field');
    }

    public function dctStatus(){
        return $this->hasOne('App\AppsSettings','id','dependency_dct_status')->select('id', 'value', 'field');
    }

    public function updateExecPath(){
        return $this->hasOne('App\AppsSettings','id','dependency_update_exec_path')->select('id', 'value', 'field');
    }

    public function updateInstallNotes(){
        return $this->hasOne('App\AppsSettings','id','dependency_update_install_notes')->select('id', 'value', 'field');
    }

    public function eda(){
        return $this->hasOne('App\AppsSettings','id','dependency_eda')->select('id', 'value', 'field');
    }

    public function appBuild(){
        return $this->hasOne('App\AppsSettings','id','dependency_app_build')->select('id', 'value', 'field');
    }

    public function type(){
        return $this->hasOne('App\AppsSettings','id','dependency_type')->select('id', 'value', 'field');
    }
}
