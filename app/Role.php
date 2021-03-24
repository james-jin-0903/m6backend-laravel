<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "roles";

    public function taxonomies()
    {
      return $this->hasMany('App\RoleTaxonomies', 'role_id', 'id');
    }
}
