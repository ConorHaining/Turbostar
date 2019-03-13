<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParseScheduleTest extends TestCase
{

    protected function setUp()
    {
        parent::setUp();

        mkdir(__DIR__.'/../ParseScheduleTestTemp');
    }


    public function testFullFileDownload()
    {
        // $command = $this->artisan('parse:schedule', ['--full' => true]);

        // dd($command);
    }

    public function testDailyFileDownload()
    {
        $this->assertTrue(true);
    }

    public function tearDown()
    {
        parent::tearDown();

        exec('rm -rf '.__DIR__.'/../ParseScheduleTestTemp');
    }
}
