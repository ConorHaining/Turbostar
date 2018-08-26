<?php

namespace App;

use Basemkhirat\Elasticsearch\Model;
// use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class TiplocModel extends Model
{
  // use SoftDeletes;

  protected $index = 'tiploc';

  protected $type = 'tiploc';

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
