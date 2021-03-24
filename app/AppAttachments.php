<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppAttachments extends Model {
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "app_attachments";

    protected $fillable = [
      'file_type', 'file_extension', 'file_size', 'file_name', 'file_name_full', 'file_path', 'file_url'
    ];

    protected $hidden = [
        'deleted_at'
    ];

}
