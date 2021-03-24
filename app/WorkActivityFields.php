<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkActivityFields extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "work_activity_fields";

    protected $fillable = [
        'setting_id', 'field_id', 'app_id'
    ];

    protected $hidden = [
        'deleted_at'
    ];

  public function setting()
  {
    return $this->belongsTo('App\AppSettings', 'id', 'setting_id');
  }

  public function app_field()
  {
    return $this->hasOne('App\AppFields', 'id', 'field_id');
  }
}
