<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\ScheduleModel;
use App\Jobs\ScheduleDelete;

class ScheduleDeleteTest extends TestCase
{
    public function testGoldenExample()
    {
      $text = '{"JsonScheduleV1":{"CIF_train_uid":"C58801","schedule_start_date":"2017-12-18","CIF_stp_indicator":"O","transaction_type":"Delete"}}';
      $text = json_decode($text);
      $payload = $text->JsonScheduleV1;

      $testSchedule = ScheduleModel::create([
        'uid' => 'C58801',
        'start_date' => '2017-12-18',
      ]);
      $testSchedule->stp_indicator = 'O';
      $testSchedule->save();

      $job = new ScheduleDelete($payload);

      $this->assertGreaterThan(0, $job->handle(), "Model has not deleted");
    }
}
