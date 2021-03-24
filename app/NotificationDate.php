<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationDate extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "notification_date";

    protected $fillable = [
        'date', 'recurrence', 'daily_every_x_day', 'daily_every_weekday',
        'weekly_recur_every_x_week', 'monthly_day', 'monthly_every_month',
        'monthly_ordinal', 'monthly_month', 'yearly_recur_years', 'yearly_day',
        'yearly_ordinal', 'yearly_month', 'yearly_week_day',
    ];

    protected $hidden = [
        'deleted_at', 'yearly_week_day', 'yearly_month', 'yearly_ordinal', 'monthly_month',
        'monthly_ordinal'
    ];

    public function yearlyWeekDay(){
        return $this->hasOne('App\AppsSettings','id','yearly_week_day')->select('id', 'value', 'field');
    }
    public function yearlyMonth(){
        return $this->hasOne('App\AppsSettings','id','yearly_month')->select('id', 'value', 'field');
    }
    public function yearlyOrdinal(){
        return $this->hasOne('App\AppsSettings','id','yearly_ordinal')->select('id', 'value', 'field');
    }
    public function monthlyMonth(){
        return $this->hasOne('App\AppsSettings','id','monthly_month')->select('id', 'value', 'field');
    }
    public function monthlyOrdinal(){
        return $this->hasOne('App\AppsSettings','id','monthly_ordinal')->select('id', 'value', 'field');
    }
}
