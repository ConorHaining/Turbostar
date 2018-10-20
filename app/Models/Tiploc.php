<?php

namespace App\Models;

use Basemkhirat\Elasticsearch\Model;

class Tiploc extends Model
{

  protected $index = 'tiploc201810201';

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
    'name',
    'location',
  ];
}
