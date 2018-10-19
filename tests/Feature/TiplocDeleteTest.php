<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Tiploc;
use App\Jobs\TiplocDelete;

class TiplocDeleteTest extends TestCase
{
  public function testGoldenExample()
  {
    $text = '{"TiplocV1":{"transaction_type":"Delete","tiploc_code":"WLGFSTN"}}';
    $text = json_decode($text);
    $payload = $text->TiplocV1;

    $testTiploc = new Tiploc();
    $testTiploc->code = 'WLGFSTN';
    $testTiploc->save();

    sleep(1);

    $job = new TiplocDelete($payload);

    $this->assertTrue($job->handle()->exists, "Model no longer exists");
    $this->assertFalse($job->handle()->attributes['active'], "Model has been set to inactive");
  }
}