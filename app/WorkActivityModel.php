<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkActivityModel extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "work_activity";

    protected $fillable = [
        'activity_number', 'title', 'description', 'author', 'application_id', 'record_id', 'status', 'app_record_id',
        'type', 'requested_date', 'start_date', 'due_date', 'end_date', 'post_id', 'company_id', 'appointment_time',
        'meeting_time', 'location', 'colors', 'father_post_id'
    ];

    protected $hidden = [
        'deleted_at', 'record_id'
    ];

    public function woAssignments(){
        return $this->hasMany('App\WoAssignments','work_id', 'id')->orderBy('status', 'DESC');
    }
    public function record(){
        return $this->hasOne('App\AppRecords', 'id', 'record_id')->with('app')->select('id', 'app_id', 'record_number', 'title', 'author', 'description', 'image', 'status');
    }
    public function application(){
      return $this->hasOne('App\M6Apps', 'id', 'application_id')->select('id', 'app_number', 'app_type', 'title', 'iconLink', 'prefix', 'author', 'description');
    }

  public function type()
  {
    return $this->hasOne('App\AppsSettings', 'id', 'type');
  }
}
