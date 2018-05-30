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
    return $this->hasMany('App\ScheduleModel');
  }

  public function assoc_train()
  {
    return $this->hasMany('App\ScheduleModel');
  }

  public function location()
  {
    return $this->hasOne('App\LocationRecord');
  }

  /**
   * Mutator to validate the category
   *
   * @param string $category
   * @return void
   */
   public function setCategoryAttribute($category)
   {
     $validValues = ['JJ', 'VV', 'NP'];

     if (in_array($category, $validValues)) {

       $this->attributes['category'] = $category;

     } else if(empty(trim($category))) {

       $this->attributes['category'] = null;

     } else {

       $this->attributes['fails_validation'] = true;

     }

   }

   /**
    * Mutator to validate the date indicator
    *
    * @param string $dateIndicator
    * @return void
    */
    public function setDateIndicatorAttribute($dateIndicator)
    {
      $validValues = ['S', 'N', 'P'];

      if (in_array($dateIndicator, $validValues)) {

        $this->attributes['date_indicator'] = $dateIndicator;

      } else if(empty(trim($dateIndicator))) {

        $this->attributes['date_indicator'] = null;

      } else {

        $this->attributes['fails_validation'] = true;

      }

    }

    /**
     * Mutator to validate the stp indicator
     *
     * @param string $stpIndicator
     * @return void
     */
     public function setStpIndicatorAttribute($stpIndicator)
     {
       $validValues = ['C', 'N', 'O', 'P'];

       if (in_array($stpIndicator, $validValues)) {

         $this->attributes['stp_indicator'] = $stpIndicator;

       } else {

         $this->attributes['fails_validation'] = true;

       }

     }


}
