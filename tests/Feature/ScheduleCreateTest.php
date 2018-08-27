<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Jobs\ScheduleCreate;

class ScheduleCreateTest extends TestCase
{
    public function testGoldenExample()
    {
        $text = '{"JsonScheduleV1":{"CIF_bank_holiday_running":null,"CIF_stp_indicator":"P","CIF_train_uid":"G04559","applicable_timetable":"Y","atoc_code":"LE","new_schedule_segment":{"traction_class":"","uic_code":""},"schedule_days_runs":"1101000","schedule_end_date":"2018-05-17","schedule_segment":{"signalling_id":"5N04","CIF_train_category":"EE","CIF_headcode":"","CIF_course_indicator":1,"CIF_train_service_code":"21945001","CIF_business_sector":"??","CIF_power_type":"EMU","CIF_timing_load":"360","CIF_speed":"100","CIF_operating_characteristics":"D","CIF_train_class":null,"CIF_sleepers":null,"CIF_reservations":null,"CIF_connection_indicator":null,"CIF_catering_code":null,"CIF_service_branding":"","schedule_location":[{"location_type":"LO","record_identity":"LO","tiploc_code":"ILFEMUD","tiploc_instance":null,"departure":"0452","public_departure":null,"platform":null,"line":null,"engineering_allowance":null,"pathing_allowance":null,"performance_allowance":null},{"location_type":"LI","record_identity":"LI","tiploc_code":"ILFELEJ","tiploc_instance":null,"arrival":null,"departure":null,"pass":"0500","public_arrival":null,"public_departure":null,"platform":null,"line":"EL","path":null,"engineering_allowance":null,"pathing_allowance":null,"performance_allowance":null},{"location_type":"LI","record_identity":"LI","tiploc_code":"ILFORD","tiploc_instance":null,"arrival":null,"departure":null,"pass":"0501","public_arrival":null,"public_departure":null,"platform":"3","line":"EL","path":"EL","engineering_allowance":null,"pathing_allowance":null,"performance_allowance":null},{"location_type":"LI","record_identity":"LI","tiploc_code":"FRSTGTJ","tiploc_instance":null,"arrival":null,"departure":null,"pass":"0503","public_arrival":null,"public_departure":null,"platform":null,"line":"EL","path":"EL","engineering_allowance":null,"pathing_allowance":null,"performance_allowance":null},{"location_type":"LI","record_identity":"LI","tiploc_code":"STFD","tiploc_instance":null,"arrival":null,"departure":null,"pass":"0505","public_arrival":null,"public_departure":null,"platform":"5","line":"EL","path":"EL","engineering_allowance":"1","pathing_allowance":null,"performance_allowance":null},{"location_type":"LI","record_identity":"LI","tiploc_code":"BOWJ","tiploc_instance":null,"arrival":null,"departure":null,"pass":"0507H","public_arrival":null,"public_departure":null,"platform":null,"line":"EL","path":"EL","engineering_allowance":null,"pathing_allowance":null,"performance_allowance":null},{"location_type":"LI","record_identity":"LI","tiploc_code":"BTHNLGR","tiploc_instance":null,"arrival":null,"departure":null,"pass":"0509H","public_arrival":null,"public_departure":null,"platform":null,"line":"EL","path":"EL","engineering_allowance":null,"pathing_allowance":null,"performance_allowance":null},{"location_type":"LT","record_identity":"LT","tiploc_code":"LIVST","tiploc_instance":null,"arrival":"0513","public_arrival":null,"platform":"14","path":null}]},"schedule_start_date":"2017-12-11","train_status":"P","transaction_type":"Create"}}';
        $text = json_decode($text);

        $payload = $text->JsonScheduleV1;

        $job = new ScheduleCreate($payload);

        $this->assertTrue($job->handle()->exists, "Model has not saved");
    }
}
