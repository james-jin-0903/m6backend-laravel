<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppFieldValueAttachment extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "field_value_attachment";

    protected $fillable = [
        'record_id', 'field_id', 'value', 'table_row_id'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function attachment(){
      return $this->hasMany('App\AppAttachments', 'id', 'value');
  }

}
