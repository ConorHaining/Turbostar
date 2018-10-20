<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Console\Commands\ParseSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ScheduleCreate;
use App\Jobs\ScheduleDelete;
use App\Jobs\AssociationCreate;
use App\Jobs\AssociationDelete;
use App\Jobs\TiplocCreate;
use App\Jobs\TiplocDelete;

class ParseScheduleTest extends TestCase
{
    protected static $command;

    protected function setUp()
    {
        parent::setUp();

        self::$command = new ParseSchedule();

    }

    public function testDailyFileDownload()
    {

        self::$command->downloadDailyFile();

        $date = Carbon::today();
        $date = $date->format('Y-m-d');

        $this->assertFileExists(storage_path('app/schedule/' . $date . '.gz'), "SCHEDULE should exist");

    }

    public function testDecompressFile()
    {
        $filepath = self::$command->downloadDailyFile();
        self::$command->decompressFile($filepath);

        $date = Carbon::today();
        $date = $date->format('Y-m-d');

        $this->assertFileExists(storage_path('app/schedule/' . $date . '.json'), "File not decompressed");
    }

    public function testCreateSchedule()
    {
        Queue::fake();

        $schedule = '{"JsonScheduleV1":{"transaction_type":"Create"}}';

        self::$command->queueSchedule($schedule);

        Queue::assertPushedOn('schedule-create', ScheduleCreate::class);
    }

    public function testDeleteSchedule()
    {
        Queue::fake();

        $schedule = '{"JsonScheduleV1":{"transaction_type":"Delete"}}';

        self::$command->queueSchedule($schedule);

        Queue::assertPushedOn('schedule-delete', ScheduleDelete::class);
    }

    public function testInvalidSchedule()
    {
        Queue::fake();

        $schedule = '{"JsonScheduleV1":{"transaction_type":"Invalid"}}';

        $this->expectException(\Exception::class);
        self::$command->queueSchedule($schedule);

        Queue::assertNotPushed('schedule-create');
        Queue::assertNotPushed('schedule-delete');
    }

    public function testCreateAssociation()
    {
        Queue::fake();

        $association = '{"JsonAssociationV1":{"transaction_type":"Create"}}';

        self::$command->queueAssociation($association);

        Queue::assertPushedOn('association-create', AssociationCreate::class);
    }

    public function testDeleteAssociation()
    {
        Queue::fake();

        $association = '{"JsonAssociationV1":{"transaction_type":"Delete"}}';

        self::$command->queueAssociation($association);

        Queue::assertPushedOn('association-delete', AssociationDelete::class);
    }

    public function testInvalidAssociation()
    {
        Queue::fake();

        $association = '{"JsonAssociationV1":{"transaction_type":"Invalid"}}';

        $this->expectException(\Exception::class);
        self::$command->queueAssociation($association);

        Queue::assertNotPushed('association-create');
        Queue::assertNotPushed('association-delete');
    }

    public function testCreateTiploc()
    {
        Queue::fake();

        $tiploc = '{"TiplocV1":{"transaction_type":"Create"}}';

        self::$command->queueTiploc($tiploc);

        Queue::assertPushedOn('tiploc-create', TiplocCreate::class);
    }

    public function testDeleteTipcloc()
    {
        Queue::fake();

        $tiploc = '{"TiplocV1":{"transaction_type":"Delete"}}';

        self::$command->queueTiploc($tiploc);

        Queue::assertPushedOn('tiploc-delete', TiplocDelete::class);
    }

    // public function testUpdateTipcloc()
    // {
    //   Queue::fake();
    //
    //   $tiploc = '{"TiplocV1":{"transaction_type":"Update"}}';
    //
    //   self::$command->queueTiploc($tiploc);
    //
    //   Queue::assertPushedOn('tiploc-update', TiplocCreate::class);
    // }

    public function testInvalidTiploc()
    {
        Queue::fake();

        $tiploc = '{"TiplocV1":{"transaction_type":"Invalid"}}';

        $this->expectException(\Exception::class);
        self::$command->queueTiploc($tiploc);

        Queue::assertNotPushed('tiploc-create');
        Queue::assertNotPushed('tiploc-delete');
        Queue::assertNotPushed('tiploc-update');
    }

    public function tearDown()
    {
        parent::tearDown();

        exec('rm -rf '.__DIR__.'/../ParseScheduleTestTemp');
    }
}
