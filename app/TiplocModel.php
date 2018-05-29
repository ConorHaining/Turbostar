<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class TiplocModel extends Model
{
  use SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   * @see https://wiki.openraildata.com/index.php/Tiploc_Records
   */
  protected $fillable = [
    'code',
    'nalco',
    'stanox',
    'crs',
    'description',
    'name'
  ];
}
