<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppsSettings extends Model {
    use SoftDeletes;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "apps_settings";

    protected $fillable = [
        'field', 'value', 'app_type', 'parent_id'
    ];

    protected $hidden = [
        'deleted_at'
    ];

  public function fields()
  {
    return $this->hasMany('App\WorkActivityFields', 'setting_id', 'id');
  }
}
