<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstallationAttachments extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "itapps_installation_attachments";

    protected $fillable = [
        'app_id', 'file_name', 'file_url', 'attachment', 'revision_notes'
    ];

    protected $hidden = [
        'deleted_at'
    ];
}
