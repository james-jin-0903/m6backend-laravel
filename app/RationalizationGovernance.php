<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RationalizationGovernance extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_rationalization_governance";

    protected $fillable = [
        'app_id', 'primary_customer_group', 'estimated_days_to_replace', 'responsible_division', 'responsible_committee',
        'responsible_manager', 'responsible_vp_dir', 'first_contact_group'
    ];

    protected $hidden = [
        'deleted_at', 'first_contact_group', 'responsible_committee', 'responsible_division'
    ];

    public function firstContactGroup(){
        return $this->hasOne('App\AppsSettings','id','first_contact_group')->select('id', 'value', 'field');
    }
    public function responsibleCommittee(){
        return $this->hasOne('App\AppsSettings','id','responsible_committee')->select('id', 'value', 'field');
    }
    public function responsibleDivision(){
        return $this->hasOne('App\AppsSettings','id','responsible_division')->select('id', 'value', 'field');
    }
}
