<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactNotification extends Model {
    use SoftDeletes;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "contact_notification";

    protected $fillable = [
        'contact_id', 'notification_id'
    ];

    protected $hidden = [
        'deleted_at', 'notification_id'
    ];

    public function notification(){
        return $this->hasOne('App\Notifications','id','notification_id');
    }
}
