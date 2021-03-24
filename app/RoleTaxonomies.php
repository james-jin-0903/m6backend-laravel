<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleTaxonomies extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $table = "role_taxonomies";

  public function role()
  {
    return $this->belongsTo('App\Role', 'role_id', 'id');
  }

  public function taxonomy()
  {
    return $this->belongsTo('App\TaxonomyTerms', 'taxonomy_id', 'id');
  }
}
