<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\ScheduleModel;
use App\TiplocModel;
use App\Jobs\AssociationCreate;

class AssociationCreateTest extends TestCase
{
  public function testGoldenExample()
  {

      $mainTrain = ScheduleModel::create(['uid' => 'G72086']);
      $mainTrain->save();

      $assocTrain = ScheduleModel::create(['uid' => 'G72088']);
      $assocTrain->save();

      $location = TiplocModel::create(['code' => 'WORTHNG']);
      $location->save();

      $text = '{"JsonAssociationV1":{"transaction_type":"Create","main_train_uid":"G72086","assoc_train_uid":"G72088","assoc_start_date":"2017-12-10T00:00:00Z","assoc_end_date":"2018-05-13T00:00:00Z","assoc_days":"0000001","category":"JJ","date_indicator":"S","location":"WORTHNG","base_location_suffix":null,"assoc_location_suffix":null,"diagram_type":"T","CIF_stp_indicator":"P"}}';
      $text = json_decode($text);

      $payload = $text->JsonAssociationV1;

      $job = new AssociationCreate($payload);

      $this->assertTrue($job->handle(), "Model has not saved");

  }

  public function testBlankCategoryAndBlankDateIndicatorField()
  {

      $mainTrain = ScheduleModel::create(['uid' => 'G72639']);
      $mainTrain->save();

      $assocTrain = ScheduleModel::create(['uid' => 'G79049']);
      $assocTrain->save();

      $location = TiplocModel::create(['code' => 'ROYSTON']);
      $location->save();

      $text = '{"JsonAssociationV1":{"transaction_type":"Create","main_train_uid":"G72639","assoc_train_uid":"G79049","assoc_start_date":"2017-12-25T00:00:00Z","assoc_end_date":"2017-12-29T00:00:00Z","assoc_days":"1111100","category":"  ","date_indicator":" ","location":"ROYSTON","base_location_suffix":null,"assoc_location_suffix":null,"diagram_type":"T","CIF_stp_indicator":"C"}}
';
      $text = json_decode($text);

      $payload = $text->JsonAssociationV1;

      $job = new AssociationCreate($payload);

      $this->assertTrue($job->handle(), "Model has not saved");

  }

}
