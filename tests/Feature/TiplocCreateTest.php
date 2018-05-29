<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Jobs\TiplocCreate;

class TiplocCreateTest extends TestCase
{
    public function testGoldenExample()
    {
        $text = '{"TiplocV1":{"transaction_type":"Create","tiploc_code":"WCROYDN","nalco":"541100","stanox":"87651","crs_code":"WCY","description":"WEST CROYDON","tps_description":"WEST CROYDON"}}';
        $text = json_decode($text);

        $payload = $text->TiplocV1;

        $job = new TiplocCreate($payload);

        $this->assertTrue($job->handle(), "Model has not saved");

    }

    public function testMissing1Example()
    {
        $text = '{"TiplocV1":{"transaction_type":"Create","tiploc_code":"WADH432","nalco":"523111","stanox":"89387","crs_code":null,"description":null,"tps_description":"WADHURST SIG PE432"}}';
        $text = json_decode($text);

        $payload = $text->TiplocV1;

        $job = new TiplocCreate($payload);

        $this->assertTrue($job->handle(), "Model has not saved");

    }

    public function testMissing2Example()
    {
        $text = '{"TiplocV1":{"transaction_type":"Create","tiploc_code":"VICTRIA","nalco":"542600","stanox":"87201","crs_code":"VIC","description":"LONDON VICTORIA","tps_description":"LONDON VICTORIA"}}';
        $text = json_decode($text);

        $payload = $text->TiplocV1;

        $job = new TiplocCreate($payload);

        $this->assertTrue($job->handle(), "Model has not saved");

    }
}
