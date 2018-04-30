<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Console\Commands\ParseSchedule;
use Carbon\Carbon;

class ParseScheduleTest extends TestCase
{
    protected static $command;

    protected function setUp()
    {
      parent::setUp();

      self::$command = new ParseSchedule();

      mkdir(__DIR__.'/ParseScheduleTestTemp');
    }

    public function testFullFileDownload()
    {

      self::$command->downloadFullFile();

      $date = Carbon::today();
      $date = $date->format('Y-m-d');

      $this->assertFileExists(__DIR__.'/ParseScheduleTestTemp/'.$date.'.gz2', "SCHEDULE should exist");

    }

    // public function testDailyFileDownload()
    // {
    //   // run function
    //   // asset file is created
    // }

    public function tearDown()
    {
        parent::tearDown();

        exec('rm -rf '.__DIR__.'/ParseScheduleTestTemp');
    }
}
