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

      $runningDays = "0100101";

      $result = $schedule->setRunningDays($runningDays);

      $this->assertTrue($result);
      $this->assertEquals($schedule->running_days, $runningDays);
    }

    public function testSetRunningDaysInvalid()
    {
      $schedule = new ScheduleModel();

      $runningDays = "010010"; // Too few characters

      $result = $schedule->setRunningDays($runningDays);

      $this->assertFalse($result, "Fails for too few characters");

      $schedule = new ScheduleModel();

      $runningDays = "abc1234"; // Invalid characters but valid length

      $result = $schedule->setRunningDays($runningDays);

      $this->assertFalse($result, "Fails for invalid characters but valid length");

      $schedule = new ScheduleModel();

      $runningDays = "abc101"; // Invalid characters and invlaid length

      $result = $schedule->setRunningDays($runningDays);

      $this->assertFalse($result, "Fails for invalid characters and invalid length");

    }

}
