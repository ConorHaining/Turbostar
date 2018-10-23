<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Schedule;
use App\Jobs\ScheduleDelete;

class ScheduleDeleteTest extends TestCase
{
    public function testGoldenExample()
    {
        $text = '{"JsonScheduleV1":{"CIF_train_uid":"C58801","schedule_start_date":"2017-12-18","CIF_stp_indicator":"O","transaction_type":"Delete"}}';
        $text = json_decode($text);
        $payload = $text->JsonScheduleV1;

        $testSchedule = new Schedule();
        $testSchedule->uid = 'C58801';
        $testSchedule->start_date = '2017-12-18';
        $testSchedule->stp_indicator = 'O';

        $testSchedule->save();

        sleep(2);

        $job = new ScheduleDelete($payload);

        $this->assertTrue($job->handle()->exists, "Model stil exists");
        $this->assertFalse($job->handle()->attributes['active'], "Model has been set to inactive");
    }
}
