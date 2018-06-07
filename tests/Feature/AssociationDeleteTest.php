<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\AssociationModel;
use App\ScheduleModel;
use App\TiplocModel;
use App\Jobs\AssociationDelete;

class AssociationDeleteTest extends TestCase
{
    public function testGoldenExample()
    {
      $text = '{"JsonAssociationV1":{"transaction_type":"Delete","main_train_uid":"P13474","assoc_train_uid":"V00975","assoc_start_date":"2017-12-22T00:00:00Z","location":"CRDFCEN","base_location_suffix":null,"diagram_type":"T","CIF_stp_indicator":"N"}}';
      $text = json_decode($text);
      $payload = $text->JsonAssociationV1;

      $mainTrain = ScheduleModel::create(['uid' => 'P13474']);
      $mainTrain->save();

      $assocTrain = ScheduleModel::create(['uid' => 'V00975']);
      $assocTrain->save();

      $location = TiplocModel::create(['code' => 'CRDFCEN']);
      $location->save();

      $testAssociation = AssociationModel::create([
        'start_date' => '2017-12-22T00:00:00Z',
        'base_location_suffix' => null
      ]);
      $testAssociation->main_train = $mainTrain;
      $testAssociation->assoc_train = $assocTrain;
      $testAssociation->location = $location;
      $testAssociation->stp_indicator = 'N';
      $testAssociation->save();

      $job = new AssociationDelete($payload);

      $this->assertGreaterThan(0, $job->handle(), "Model has not deleted");
    }
}
