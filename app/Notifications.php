<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notifications extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "notifications";

    protected $fillable = [
        'app_id', 'name', 'date', 'notification_required', 'description'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function notificationDate(){
        return $this->hasOne('App\NotificationDate','id', 'date')->with([
            'yearlyWeekDay', 'yearlyMonth', 'yearlyOrdinal', 'monthlyMonth', 'monthlyOrdinal'
        ]);
    }

    public function notiCont(){
        return $this->hasMany('App\ContactNotification', 'notification_id', 'id')->orderByRaw('id');
    }
}
