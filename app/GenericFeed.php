<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GenericFeed extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "generic_feeds";

    protected static function boot() {
      parent::boot();

      static::deleting(function($group) {
          $group->users()->delete();
      });
    }


    protected $fillable = [
      'key', 'name', 'owner_id', 'description'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function users() {
      return $this->hasMany('App\GSFeedUser','feed_id', 'id');
    }

}
