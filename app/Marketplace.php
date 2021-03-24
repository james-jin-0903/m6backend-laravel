<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marketplace extends Model {
  /**
   * Table name
   *
   * @var string
   */
  protected $table = 'marketplace'; 

  /**
   * Fillable columns
   *
   * @var string[]
   */
  protected $fillable = [
    'app_id', 'status', 'overview'
  ];

  /**
   * Not using timestamps
   *
   * @var boolean
   */
  public $timestamps = false;

  public function app() {
    return $this->belongsTo(M6Apps::class);
  }

  public function media() {
    return $this->belongsToMany(AppAttachments::class, 'marketplace_media', 'app_id', 'file_id')
      ->withPivot('id');
  }
}
