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

}
