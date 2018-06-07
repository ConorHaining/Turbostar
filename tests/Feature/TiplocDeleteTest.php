<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\TiplocModel;
use App\Jobs\TiplocDelete;

class TiplocDeleteTest extends TestCase
{
  public function testGoldenExample()
  {
    $text = '{"TiplocV1":{"transaction_type":"Delete","tiploc_code":"WLGFSTN"}}';
    $text = json_decode($text);
    $payload = $text->TiplocV1;

    $testTiploc = TiplocModel::create([
      'code' => 'WLGFSTN'
    ]);
    $testTiploc->save();

    $job = new TiplocDelete($payload);

    $this->assertGreaterThan(0, $job->handle(), "Model has not deleted");
  }
}
