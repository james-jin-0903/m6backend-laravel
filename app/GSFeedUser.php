<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GSFeedUser extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "feed_users";

    protected $fillable = [
      'user_id', 'role', 'feed_id'
    ];

    protected $hidden = [
      'feed_id'
    ];

    public function FeedGroup() {
      return $this->belongsTo('App\GenericFeed', 'feed_id');
    }

}
