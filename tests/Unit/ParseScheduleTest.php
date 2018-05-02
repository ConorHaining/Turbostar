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

    public function testDailyFileDownload()
    {

      self::$command->downloadDailyFile();

      $date = Carbon::today();
      $date = $date->format('Y-m-d');

      $this->assertFileExists(__DIR__.'/ParseScheduleTestTemp/'.$date.'.gz2', "SCHEDULE should exist");

    }

    public function testNonExistingHeader()
    {
        $header = '{"JsonTimetableV1":{"classification":"public","timestamp":1520294716,"owner":"Network Rail","Sender":{"organisation":"Rockshore","application":"NTROD","component":"SCHEDULE"},"Metadata":{"type":"full","sequence":2091}}}';

        $doesHeaderExist = self::$command->isHeaderValid($header);

        $this->assertTrue($doesHeaderExist, "This header shouldn't exist in the database");
    }

    public function testExistingHeader()
    {
      $header = '{"JsonTimetableV1":{"classification":"public","timestamp":1520294716,"owner":"Network Rail","Sender":{"organisation":"Rockshore","application":"NTROD","component":"SCHEDULE"},"Metadata":{"type":"full","sequence":1897}}}';

      $doesHeaderExist = self::$command->isHeaderValid($header);

      $this->assertFalse($doesHeaderExist, "This header shouldn't exist in the database");
    }

    public function tearDown()
    {
        parent::tearDown();

        exec('rm -rf '.__DIR__.'/ParseScheduleTestTemp');
    }
}
