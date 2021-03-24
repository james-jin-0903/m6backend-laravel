<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class M6Apps extends Model {
    use SoftDeletes;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "m6_apps";

    protected $fillable = [
        'title', 'description', 'author',
        'app_type', 'app_number',
        'iconLink', 'prefix', 'metadata',
        'layout_type'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function records() {
        return $this->hasMany('App\AppRecords', 'app_id', 'id')->orderBy('created_at', 'asc');
    }

    // AppBuilder
    public function tabs(){
        return $this->hasMany('App\AppTabs', 'app_id', 'id')->orderBy('order', 'asc');
    }

    public function fields(){
        return $this->hasMany('App\AppFields', 'app_id', 'id')->where('panel_id', '=', null)->orderBy('id', 'asc');
    }

    public function fields_panel() {
        return $this->hasMany('App\AppFields', 'app_id', 'id')->where('panel_id', '<>', null)->orderBy('id', 'asc');
    }

    // ITApp functions
    public function generalInfo(){
        return $this->hasOne('App\AppInfoGeneral','app_id','id')->with([
            'status', 'firstContactGroup', 'category', 'subCategory', 'type',
            'appManagement', 'serverHostingModel', 'capability'
        ]);
    }
    public function informationSecurity(){
        return $this->hasOne('App\InformationSecurity','app_id','id')->with('ssnForeign');
    }
    public function imageInfo(){
        return $this->hasOne('App\Image','app_id','id');
    }
    public function alsoKnown(){
        return $this->hasMany('App\TagsModel','foreign_id', 'id')->where('field', 'also_know_as')->orderByRaw('id');
    }
    public function formerlyKnown(){
        return $this->hasMany('App\TagsModel','foreign_id', 'id')->where('field', 'formerly_known_as')->orderByRaw('id');
    }
    // Installations functions
    public function installationGeneral(){
        return $this->hasOne('App\InstallationGenerals','app_id', 'id')->with([
            'installType', 'ldapAdAuthentication', 'windowsPassedDct', 'deliveryMethod'
        ]);
    }
    public function installationAttachment(){
        return $this->hasOne('App\InstallationAttachments','app_id', 'id');
    }
    public function installationSupport(){
        return $this->hasOne('App\InstallationSupport','app_id', 'id');
    }
    public function installationAditionalInformation(){
        return $this->hasOne('App\InstallationAditionalInformation','app_id', 'id');
    }
    // Rationalization functions
    public function rationalizationAttributes(){
        return $this->hasOne('App\RationalizationAttributes','app_id', 'id')->with([
            'capability', 'rationalizationKind'
        ]);
    }
    public function rationalizationCosts(){
        return $this->hasOne('App\RationalizationCosts','app_id', 'id')->with([
            'getPeriod', 'owner', 'type', 'category'
        ]);
    }
    public function rationalizationFte(){
        return $this->hasOne('App\RationalizationFte','app_id', 'id');
    }
    public function rationalizationGovernance(){
        return $this->hasOne('App\RationalizationGovernance','app_id', 'id')->with([
            'firstContactGroup', 'responsibleCommittee', 'responsibleDivision'
        ]);
    }
    public function rationalizationLicensing(){
        return $this->hasOne('App\RationalizationLicensing','app_id', 'id')->with(['licenseType','purchaseType']);
    }
    public function rationalizationUsers(){
        return $this->hasOne('App\RationalizationUsers','app_id', 'id');
    }
    // Specification functions
    public function specificationCertification(){
        return $this->hasOne('App\SpecificationCertifications','app_id', 'id');
    }
    public function specificationMaintenance(){
        return $this->hasOne('App\SpecificationMaintenances','app_id', 'id')->with([
            'networkZoneType', 'ipAddressType', 'futureOpSystem', 'operatingSystem'
        ]);
    }
    public function specificationMonitoring(){
        return $this->hasOne('App\SpecificationMonitoring','app_id', 'id');
    }
}
