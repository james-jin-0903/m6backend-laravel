<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstallationSupport extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_installation_support";

    protected $fillable = [
        'app_id', 'firewall_exceptions', 'firewall_exceptions_note', 'install_notes', 'install_notes_note',
        'mapped_drives', 'mapped_drives_note', 'registry_changes', 'registry_changes_note',
        'antivirus_exclusion', 'antivirus_exclusion_note', 'ini_changes', 'ini_changes_note',
        'shortcut_modifications', 'shortcut_modifications_note'
    ];

    protected $hidden = [
        'deleted_at'
    ];
}
