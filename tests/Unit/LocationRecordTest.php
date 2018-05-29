<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\LocationRecord;

class LocationRecordTest extends TestCase
{
  public function testSetTypeValid()
  {
    $record = new LocationRecord();
    $this->assertTrue(method_exists($record, 'setTypeAttribute'),  'Class does not have setTypeAttribute method');

    $validValues = ['LO', 'LI', 'LT'];

    foreach ($validValues as $value) {

      $record = new LocationRecord();

      $record->type = $value;

      $this->assertEquals($value, $record->type, "Fails for invalid string: ".$value);

    }

  }

  public function testSetServiceBrandingInvalid()
  {
    $record = new LocationRecord();
    $this->assertTrue(method_exists($record, 'setTypeAttribute'),  'Class does not have setTypeAttribute method');

    $invalidValues = ['BS', '1', 'CAT'];

    foreach ($invalidValues as $value) {

      $record = new LocationRecord();

      $record->type = $value;

      $this->assertTrue($record->fails_validation, "Fails for invalid string: ".$value);

    }

  }
}
