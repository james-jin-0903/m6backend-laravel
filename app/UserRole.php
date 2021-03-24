<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $table = "user_roles";

  public function role()
  {
    return $this->belongsTo('App\Role', 'role_id', 'id');
  }
}
