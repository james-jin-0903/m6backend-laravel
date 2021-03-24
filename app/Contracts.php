<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contracts extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_contracts";

    protected $fillable = [
        'contract_name', 'number', 'status', 'start_contract', 'finish_contract', 'term_length', 'capped_inflator',
        'critical_decision_date', 'capped_inflator_value', 'app_id', 'term_until', 'term_notice_period'
    ];

    protected $hidden = [
        'deleted_at'
    ];
}
