<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppInfoGeneral extends Model {
    use SoftDeletes;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_app_info_general";

    protected $fillable = [
        'app_id', 'vendor_id', 'version', 'status_settings_id', 'first_contact_group_settings_id',
        'category_settings_id', 'sub_category_settings_id', 'type_settings_id', 'app_management_settings_id',
        'server_hosting_model_settings_id', 'capabilities'
    ];

    protected $hidden = [
        'deleted_at', 'status_settings_id', 'first_contact_group_settings_id', 'category_settings_id',
        'sub_category_settings_id', 'type_settings_id', 'app_management_settings_id', 'server_hosting_model_settings_id',
        'capabilities'
    ];

    public function status(){
        return $this->hasOne('App\AppsSettings','id','status_settings_id')->select('id', 'value', 'field');
    }

    public function firstContactGroup(){
        return $this->hasOne('App\AppsSettings','id','first_contact_group_settings_id')->select('id', 'value', 'field');
    }

    public function category(){
        return $this->hasOne('App\AppsSettings','id','category_settings_id')->select('id', 'value', 'field');
    }

    public function subCategory(){
        return $this->hasOne('App\AppsSettings','id','sub_category_settings_id')->select('id', 'value', 'field');
    }

    public function type(){
        return $this->hasOne('App\AppsSettings','id','type_settings_id')->select('id', 'value', 'field');
    }

    public function appManagement(){
        return $this->hasOne('App\AppsSettings','id','app_management_settings_id')->select('id', 'value', 'field');
    }

    public function serverHostingModel(){
        return $this->hasOne('App\AppsSettings','id','server_hosting_model_settings_id')->select('id', 'value', 'field');
    }

    public function capability(){
        return $this->hasOne('App\AppsSettings','id','capabilities')->select('id', 'value', 'field');
    }
}
