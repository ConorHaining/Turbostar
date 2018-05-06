<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class ScheduleModel extends Model
{

  use SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   * @see https://wiki.openraildata.com/index.php/Schedule_Records
   */
  protected $fillable = [
    'uid',
    'valid',
    'start_date',
    'end_start',
    'signalling_id',
    'headcode',
    'course_indicator',
    'train_service_code',
    'portion_id',
    'speed',
    'connection_indicator',
    'traction_class',
    'uic_code',
    'schedule_locations',
  ];

  /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     * @see https://wiki.openraildata.com/index.php/Schedule_Records
     */
  protected $guarded = [
    'running_days',
    'bank_holiday_running',
    'train_status',
    'train_category',
    'power_type',
    'timing_load',
    'operating_characteristics',
    'train_class',
    'sleepers',
    'reservations',
    'catering_code',
    'service_branding',
    'stp_indicator',
    'atoc_code',
    'applicable_timetable',
  ];

  /**
   * A mutator to validate the running days of a schedule.
   * It should be 7 characters long and only contain '0's and '1's
   *
   * @param string $runningDays
   * @return void
   */
   public function setRunningDaysAttribute($runningDays)
   {

     preg_match('/(0|1)*/', $runningDays, $characterCheck);

     if( strlen($runningDays) != 7 || in_array("", $characterCheck))
     {
       $this->attributes['fails_validation'] = true;
       return;
     }

     $this->attributes['running_days'] = $runningDays;
   }

}
