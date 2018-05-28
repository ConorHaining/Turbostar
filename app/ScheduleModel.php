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

   /**
    * A mutator to validate bank holiday runnings of schedules.
    * They should only contain 'X and 'G'.
    *
    * @param string $bankholidayRunning
    * @return void
    */
    public function setBankHolidayRunningAttribute($bankholidayRunning)
    {

      if ($bankholidayRunning == null) {

        $this->attributes['bank_holiday_running'] = null;

      } else if ($bankholidayRunning != 'G' && $bankholidayRunning != 'X') {

        $this->attributes['fails_validation'] = true;
        return;

      } else {

        $this->attributes['bank_holiday_running'] = $bankholidayRunning;

      }

    }

    /**
     * A mutator to validate train status and ensure it is
     * from a valid list.
     *
     * @param string $trainStatus
     * @return void
     * @see https://wiki.openraildata.com/index.php/CIF_Codes#Train_Status
     */
     public function setTrainStatusAttribute($trainStatus)
     {
       $validValues = ['B', 'F', 'P', 'S', 'T', '1', '2', '3', '4', '5'];

       if (in_array($trainStatus, $validValues)) {

         $this->attributes['train_status'] = $trainStatus;

       } else {

         $this->attributes['fails_validation'] = true;

       }

     }

     /**
      *A mutator to validate train category and ensure it is
      * from a valid list.
      *
      * @param string $trainCategory
      * @return void
      * @see https://wiki.openraildata.com/index.php/CIF_Codes#Train_Category
      */
      public function setTrainCategoryAttribute($trainCategory)
      {
        $validValues = ['OL', 'OU', 'OO', 'OS', 'OW', 'XC', 'XD', 'XI', 'XR', 'XU', 'XX', 'XZ', 'BR', 'BS', 'SS', 'EE', 'EL', 'ES', 'JJ', 'PM', 'PP', 'PV', 'DD', 'DH', 'DI', 'DQ', 'DT', 'DY', 'ZB', 'ZZ', 'J2', 'H2', 'J3', 'J4', 'J5', 'J6', 'J8', 'H8', 'J9', 'H9', 'A0', 'E0', 'B0', 'B1', 'B4', 'B5', 'B6', 'B7', 'H0', 'H1', 'H3', 'H4', 'H5', 'H6'];

        if (in_array($trainCategory, $validValues)) {

          $this->attributes['train_category'] = $trainCategory;

        } else {

          $this->attributes['fails_validation'] = true;

        }

      }

      /**
       *A mutator to validate power type and ensure it is
       * from a valid list.
       *
       * @param string $powerType
       * @return void
       * @see https://wiki.openraildata.com/index.php/CIF_Codes#Power_Type
       */
       public function setPowerTypeAttribute($powerType)
       {
         $validValues = ['D', 'DEM', 'DMU', 'E', 'ED', 'EML', 'EMU', 'HST'];

         if (in_array($powerType, $validValues)) {

           $this->attributes['power_type'] = $powerType;

         } else {

           $this->attributes['fails_validation'] = true;

         }

       }

       /**
        * A mutator to validate operating characteristics
        *
        * @param string $operatingCharacterisitics
        * @return void
        * @see https://wiki.openraildata.com/index.php/CIF_Codes#Operating_Characteristics
        */
        public function setOperatingCharacteristicsAttribute($operatingCharacterisitics)
        {
          $validValues = ['B', 'C', 'D', 'E', 'G', 'M', 'P', 'Q', 'R', 'S', 'Y', 'Z'];

          $operatingCharacterisitics = str_split($operatingCharacterisitics);

          // Set this to an empty string allows for the concatenation below
          $this->attributes['operating_characteristics'] = "";
          foreach ($operatingCharacterisitics as $value) {

            if (in_array($value, $validValues)) {

              $this->attributes['operating_characteristics'] .= $value;

            } else {

              $this->attributes['fails_validation'] = true;

            }

          }
        }

        public function setTrainClassAttribute($trainClass)
        {
          $validValues = ['B', null, 'S'];

          if (in_array($trainClass, $validValues)) {

            $this->attributes['train_class'] = $trainClass;

          } else {

            $this->attributes['fails_validation'] = true;

          }

        }

        /**
        * A mutator to validate sleeper
        *
        * @param string $sleeper
        * @return void
        */
        public function setSleepersAttribute($sleeper)
        {
          $validValues = ['B', 'F', 'S'];

          if (in_array($sleeper, $validValues)) {

            $this->attributes['sleepers'] = $sleeper;

          } else {

            $this->attributes['fails_validation'] = true;

          }
        }

        /**
        * A mutator to validate reservations
        *
        * @param string $sleeper
        * @return void
        */
        public function setReservationsAttribute($reservations)
        {
          $validValues = ['A', 'E', 'R', 'S'];

          if (in_array($reservations, $validValues)) {

            $this->attributes['reservations'] = $reservations;

          } else {

            $this->attributes['fails_validation'] = true;

          }
        }
}
