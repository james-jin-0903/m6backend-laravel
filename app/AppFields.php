<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppFields extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "app_fields";

    protected $fillable = [
        'app_id', 'panel_id', 'weight', 'type', 'label', 'metadata', 'machine_name', 'referenced_field', 'referenced_app', 'show_in_table', 'table_index', 'referenced_record_id', 'table_id', 'order'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function getMetadataAttribute($value)
    {
        return json_decode($value);
    }

    // protected static function booted() {
    //     static::addGlobalScope('type', function (Builder $builder) {
    //         $builder->select( 'id', 'panel_id', 'weight', 'label', 'type', 'metadata',
    //             AppFields::raw(
    //                 '(CASE
    //                 WHEN type = "timestamp"    THEN "'.$this->value().'"
    //                 WHEN type = "people"       THEN (SELECT field_id FROM field_value_app_settings  where field_id = id and  )
    //                 WHEN type = "autocomplete" THEN (SELECT field_id FROM field_value_tags          where field_id = id and  )
    //                 WHEN type = "boolean"      THEN (SELECT field_id FROM field_value_boolean       where field_id = id and  )
    //                 ELSE "Undefined" END) AS value'
    //             )
    //         );
    //     });
    // }

    public function value() {
        return $this->hasOne('App\AppFieldValueDate', 'field_id', 'id');
    }
    public function valuePeople() {
        return $this->hasOne('App\AppFieldValueAppSettings', 'field_id', 'id');
    }
    public function valueTag() {
        return $this->hasMany('App\AppFieldValueTag','field_id', 'id');
    }

    public function referenced_field() {
        return $this->belongsTo('App\AppFields', 'referenced_field');
    }

    public function referenced_app() {
      return $this->belongsTo('App\AppFields', 'referenced_app');
    }

    public function AppRoles() {
        return $this->hasOne('App\AppRole','field_id', 'id');
    }
}
