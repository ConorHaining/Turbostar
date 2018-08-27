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

      $testAssociation = new AssociationModel();
      $testAssociation->start_date = '2017-12-22T00:00:00Z';
      $testAssociation->base_location_suffix = null;
      $testAssociation->main_train = 'P13474';
      $testAssociation->assoc_train = 'V00975';
      $testAssociation->location = 'CRDFCEN';
      $testAssociation->stp_indicator = 'N';
      $testAssociation->save();

      sleep(1);

      $job = new AssociationDelete($payload);

      $this->assertTrue($job->handle()->exists, "Model still exists");
      $this->assertFalse($job->handle()->attributes['active'], "Model has been set to inactive");
    }
}
