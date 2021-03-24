<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppTables extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "app_tables";

    protected $fillable = [
        'title', 'app_id', 'panel_id', 'add_totals_row', 'add_sub_totals'
    ];

    protected $hidden = [
        'deleted_at'
    ];


    public function fields() {
        return $this->hasMany('App\AppFields', 'table_id', 'id');
    }

    public function table_rows() {
        return $this->hasMany('App\AppTableRows', 'table_id', 'id');
    }

}
