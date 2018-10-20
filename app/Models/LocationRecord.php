<?php

namespace App\Models;

use Basemkhirat\Elasticsearch\Model;

class LocationRecord extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     * @see https://wiki.openraildata.com/index.php/Schedule_Records
     */
    protected $fillable = [
    'tiploc_instance',
    'arrival',
    'departure',
    'pass',
    'public_arrival',
    'public_departure',
    'platform',
    'line',
    'path',
    'engineering_allowance',
    'pathing_allowance'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     * @see https://wiki.openraildata.com/index.php/Schedule_Records
     */
    protected $guarded = [
    'type',
    'tipcloc'
    ];

    /**
     * Establish a one-to-one relationship with TIPLOCs
     *
     * @return void
     */
    public function tiploc()
    {
        return $this->hasOne('App\Tiploc');
    }

    /**
     * A mutator to valid the record type
     *
     * @param  string $type
     * @return void
     */
    public function setTypeAttribute($type)
    {
        $validValues = ['LO', 'LI', 'LT'];

        if (in_array($type, $validValues)) {

            return $type;

        } else {

            $this->attributes['fails_validation'] = true;

        }
    }
}
