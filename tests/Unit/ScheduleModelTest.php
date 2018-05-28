<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\ScheduleModel;

class ScheduleModelTest extends TestCase
{
    public function testSetRunningDaysValid()
    {
      $schedule = new ScheduleModel();
      $this->assertTrue(method_exists($schedule, 'setRunningDaysAttribute'),  'Class does not have setRunningDaysAttribute method');

      $runningDays = "0100101";

      $schedule->running_days = $runningDays;

      $this->assertEquals($runningDays, $schedule->running_days);
    }

    public function testSetRunningDaysInvalid()
    {
      $schedule = new ScheduleModel();

      $runningDays = "010010"; // Too few characters

      $schedule->running_days = $runningDays;

      $this->assertTrue($schedule->fails_validation, "Fails for too few characters");

      $schedule = new ScheduleModel();

      $runningDays = "abc1234"; // Invalid characters but valid length

      $schedule->running_days = $runningDays;

      $this->assertTrue($schedule->fails_validation, "Fails for invalid characters but valid length");

      $schedule = new ScheduleModel();

      $runningDays = "abc101"; // Invalid characters and invlaid length

      $schedule->running_days = $runningDays;

      $this->assertTrue($schedule->fails_validation, "Fails for invalid characters and invalid length");

    }

    public function testSetBankHolidayRunningValid()
    {
      $schedule = new ScheduleModel();
      $this->assertTrue(method_exists($schedule, 'setBankHolidayRunningAttribute'),  'Class does not have setBankHolidayRunningAttribute method');

      $bankholidayRunning = "X";

      $schedule->bank_holiday_running = $bankholidayRunning;

      $this->assertEquals($bankholidayRunning, $schedule->bank_holiday_running);

      $schedule = new ScheduleModel();

      $bankholidayRunning = "G";

      $schedule->bank_holiday_running = $bankholidayRunning;

      $this->assertEquals($bankholidayRunning, $schedule->bank_holiday_running);
    }

    public function testSetBankHolidayRunningInvalid()
    {
      $schedule = new ScheduleModel();

      $bankholidayRunning = "invalid";

      $schedule->bank_holiday_running = $bankholidayRunning;

      $this->assertTrue($schedule->fails_validation, "Fails for invalid character");
    }

    public function testSetTrainStatusValid()
    {
      $schedule = new ScheduleModel();
      $this->assertTrue(method_exists($schedule, 'setTrainStatusAttribute'),  'Class does not have setTrainStatusAttribute method');

      $validValues = ['B', 'F', 'P', 'S', 'T', '1', '2', '3', '4', '5'];

      foreach ($validValues as $value) {
        $schedule = new ScheduleModel();

        $schedule->train_status = $value;

        $this->assertEquals($value, $schedule->train_status, "Assert failed for value '".$value."'");

      }

    }

    public function testSetTrainStatusInvalid()
    {
      $schedule = new ScheduleModel();
      $this->assertTrue(method_exists($schedule, 'setTrainStatusAttribute'),  'Class does not have setTrainStatusAttribute method');

      $invalidValues = [null, 3.14, 'ABC', 'b'];

      foreach ($invalidValues as $value) {
        $schedule = new ScheduleModel();

        $schedule->train_status = $value;

        $this->assertTrue($schedule->fails_validation, "Fails for invalid character: '".$value."'");

      }

    }

    public function testSetTrainCategoryValid()
    {
      $schedule = new ScheduleModel();
      $this->assertTrue(method_exists($schedule, 'setTrainStatusAttribute'),  'Class does not have setTrainStatusAttribute method');

      $validValues = ['OL', 'OU', 'OO', 'OS', 'OW', 'XC', 'XD', 'XI', 'XR', 'XU', 'XX', 'XZ', 'BR', 'BS', 'SS', 'EE', 'EL', 'ES', 'JJ', 'PM', 'PP', 'PV', 'DD', 'DH', 'DI', 'DQ', 'DT', 'DY', 'ZB', 'ZZ', 'J2', 'H2', 'J3', 'J4', 'J5', 'J6', 'J8', 'H8', 'J9', 'H9', 'A0', 'E0', 'B0', 'B1', 'B4', 'B5', 'B6', 'B7', 'H0', 'H1', 'H3', 'H4', 'H5', 'H6'];

      foreach ($validValues as $value) {
        $schedule = new ScheduleModel();

        $schedule->train_category = $value;

        $this->assertEquals($value, $schedule->train_category, "Assert failed for value '".$value."'");

      }
    }

    public function testSetTrainCategoryInvalid()
    {
      $schedule = new ScheduleModel();
      $this->assertTrue(method_exists($schedule, 'setTrainCategoryAttribute'),  'Class does not have setTrainStatusAttribute method');

      $invalidValues = [null, 3.14, 'ABC', 'b'];

      foreach ($invalidValues as $value) {
        $schedule = new ScheduleModel();

        $schedule->train_category = $value;

        $this->assertTrue($schedule->fails_validation, "Fails for invalid character: '".$value."'");

      }
    }

    public function testSetPowerTypeValid()
    {
      $schedule = new ScheduleModel();
      $this->assertTrue(method_exists($schedule, 'setPowerTypeAttribute'),  'Class does not have setPowerTypeAttribute method');

      $validValues = ['D', 'DEM', 'DMU', 'E', 'ED', 'EML', 'EMU', 'HST'];

      foreach ($validValues as $value) {
        $schedule = new ScheduleModel();

        $schedule->power_type = $value;

        $this->assertEquals($value, $schedule->power_type, "Assert failed for value '".$value."'");

      }
    }

    public function testSetPowerTypeInvalid()
    {
      $schedule = new ScheduleModel();
      $this->assertTrue(method_exists($schedule, 'setPowerTypeAttribute'),  'Class does not have setPowerTypeAttribute method');

      $invalidValues = [null, 3.14, 'ABC', 'b'];

      foreach ($invalidValues as $value) {
        $schedule = new ScheduleModel();

        $schedule->power_type = $value;

        $this->assertTrue($schedule->fails_validation, "Fails for invalid character: '".$value."'");

      }
    }

    public function testSetOperatingCharacterisiticsValid()
    {
      $schedule = new ScheduleModel();
      $this->assertTrue(method_exists($schedule, 'setOperatingCharacteristicsAttribute'),  'Class does not have setOperatingCharacteristicsAttribute method');

      $validString = ['B', 'C', 'D', 'E', 'G', 'M', 'P', 'Q', 'R', 'S', 'Y', 'Z', 'BC', 'CDE', 'EGMP', 'GMPQRS'];

      foreach ($validString as $value) {

        $schedule = new ScheduleModel();

        $schedule->operating_characteristics = $value;

        $this->assertEquals($value, $schedule->operating_characteristics, "Fails for invalid string: ".$value);
      }


    }

    public function testSetOperatingCharacterisiticsInvalid()
    {
      $schedule = new ScheduleModel();
      $this->assertTrue(method_exists($schedule, 'setOperatingCharacteristicsAttribute'),  'Class does not have setOperatingCharacteristicsAttribute method');

      $invalidValues = ['CAT', 123];

      foreach ($invalidValues as $value) {

        $schedule = new ScheduleModel();

        $schedule->operating_characteristics = $value;

        $this->assertTrue($schedule->fails_validation, "Fails for invalid string: ".$value);

      }


    }
}
