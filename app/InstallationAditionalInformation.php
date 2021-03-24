<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstallationAditionalInformation extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_installation_additional_information";

    protected $fillable = [
        'app_id', 'previous_software_version', 'groups_machine', 'groups_user'
    ];

    protected $hidden = [
        'deleted_at'
    ];
}
