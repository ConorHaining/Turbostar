<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class AssociationModel extends Model
{
  use SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   * @see https://wiki.openraildata.com/index.php/Association_Records
   */
  protected $fillable = [
    'start_date',
    'end_date',
    'running_days',
    'base_location_suffix',
    'assoc_location_suffix'
  ];

  /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     * @see https://wiki.openraildata.com/index.php/Association_Records
     */
  protected $guarded = [
    'main_train',
    'assoc_train',
    'category',
    'date_indicator',
    'location',
    'stp_indicator'
  ];

  public function main_train()
  {
    return $this->hasOne('App\ScheduleModel');
  }

  public function assoc_train()
  {
    return $this->hasOne('App\ScheduleModel');
  }

  public function location()
  {
    return $this->hasOne('App\LocationRecord');
  }

}
