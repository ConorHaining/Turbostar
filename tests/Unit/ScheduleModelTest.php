<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Schedule;

class ScheduleTest extends TestCase
{
    public function testSetRunningDaysValid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setRunningDaysAttribute'),  'Class does not have setRunningDaysAttribute method');

        $runningDays = "0100101";

        $schedule->running_days = $runningDays;

        $this->assertEquals($runningDays, $schedule->running_days);
    }

    public function testSetRunningDaysInvalid()
    {
        $schedule = new Schedule();

        $runningDays = "010010"; // Too few characters

        $schedule->running_days = $runningDays;

        $this->assertEquals($schedule->running_days, null, "Fails for too few characters");

        $schedule = new Schedule();

        $runningDays = "abc1234"; // Invalid characters but valid length

        $schedule->running_days = $runningDays;

        $this->assertEquals($schedule->running_days, null, "Fails for invalid characters but valid length");

        $schedule = new Schedule();

        $runningDays = "abc101"; // Invalid characters and invlaid length

        $schedule->running_days = $runningDays;

        $this->assertEquals($schedule->running_days, null, "Fails for invalid characters and invalid length");

    }

    public function testSetBankHolidayRunningValid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setBankHolidayRunningAttribute'),  'Class does not have setBankHolidayRunningAttribute method');

        $bankholidayRunning = "X";

        $schedule->bank_holiday_running = $bankholidayRunning;

        $this->assertEquals($bankholidayRunning, $schedule->bank_holiday_running);

        $schedule = new Schedule();

        $bankholidayRunning = "G";

        $schedule->bank_holiday_running = $bankholidayRunning;

        $this->assertEquals($bankholidayRunning, $schedule->bank_holiday_running);
    }

    public function testSetBankHolidayRunningInvalid()
    {
        $schedule = new Schedule();

        $bankholidayRunning = "invalid";

        $schedule->bank_holiday_running = $bankholidayRunning;

        $this->assertEquals($schedule->bank_holiday_running, null, "Fails for invalid character");
    }

    public function testSetTrainStatusValid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setTrainStatusAttribute'),  'Class does not have setTrainStatusAttribute method');

        $validValues = ['B', 'F', 'P', 'S', 'T', '1', '2', '3', '4', '5'];

        foreach ($validValues as $value) {
            $schedule = new Schedule();

            $schedule->train_status = $value;

            $this->assertEquals($value, $schedule->train_status, "Assert failed for value '".$value."'");

        }

    }

    public function testSetTrainStatusInvalid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setTrainStatusAttribute'),  'Class does not have setTrainStatusAttribute method');

        $invalidValues = [3.14, 'ABC', 'b'];

        foreach ($invalidValues as $value) {
            $schedule = new Schedule();

            $schedule->train_status = $value;

            $this->assertEquals($schedule->train_status, null, "Fails for invalid character: '".$value."'");

        }

    }

    public function testSetTrainCategoryValid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setTrainStatusAttribute'),  'Class does not have setTrainStatusAttribute method');

        $validValues = ['OL', 'OU', 'OO', 'OS', 'OW', 'XC', 'XD', 'XI', 'XR', 'XU', 'XX', 'XZ', 'BR', 'BS', 'SS', 'EE', 'EL', 'ES', 'JJ', 'PM', 'PP', 'PV', 'DD', 'DH', 'DI', 'DQ', 'DT', 'DY', 'ZB', 'ZZ', 'J2', 'H2', 'J3', 'J4', 'J5', 'J6', 'J8', 'H8', 'J9', 'H9', 'A0', 'E0', 'B0', 'B1', 'B4', 'B5', 'B6', 'B7', 'H0', 'H1', 'H3', 'H4', 'H5', 'H6', null];

        foreach ($validValues as $value) {
            $schedule = new Schedule();

            $schedule->train_category = $value;

            $this->assertEquals($value, $schedule->train_category, "Assert failed for value '".$value."'");

        }
    }

    public function testSetTrainCategoryInvalid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setTrainCategoryAttribute'),  'Class does not have setTrainStatusAttribute method');

        $invalidValues = [3.14, 'ABC', 'b'];

        foreach ($invalidValues as $value) {
            $schedule = new Schedule();

            $schedule->train_category = $value;

            $this->assertEquals($schedule->train_category, null, "Fails for invalid character: '".$value."'");

        }
    }

    public function testSetPowerTypeValid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setPowerTypeAttribute'),  'Class does not have setPowerTypeAttribute method');

        $validValues = ['D', 'DEM', 'DMU', 'E', 'ED', 'EML', 'EMU', 'HST', null];

        foreach ($validValues as $value) {
            $schedule = new Schedule();

            $schedule->power_type = $value;

            $this->assertEquals($value, $schedule->power_type, "Assert failed for value '".$value."'");

        }
    }

    public function testSetPowerTypeInvalid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setPowerTypeAttribute'),  'Class does not have setPowerTypeAttribute method');

        $invalidValues = [3.14, 'ABC', 'b'];

        foreach ($invalidValues as $value) {
            $schedule = new Schedule();

            $schedule->power_type = $value;

            $this->assertEquals($schedule->power_type, null, "Fails for invalid character: '".$value."'");

        }
    }

    public function testSetOperatingCharacterisiticsValid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setOperatingCharacteristicsAttribute'),  'Class does not have setOperatingCharacteristicsAttribute method');

        $validString = ['B', 'C', 'D', 'E', 'G', 'M', 'P', 'Q', 'R', 'S', 'Y', 'Z', 'BC', 'CDE', 'EGMP', 'GMPQRS'];

        foreach ($validString as $value) {

            $schedule = new Schedule();

            $schedule->operating_characteristics = $value;

            $this->assertEquals($value, $schedule->operating_characteristics, "Fails for invalid string: ".$value);
        }


    }

    public function testSetOperatingCharacterisiticsInvalid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setOperatingCharacteristicsAttribute'),  'Class does not have setOperatingCharacteristicsAttribute method');

        $invalidValues = ['CAT', 123];

        foreach ($invalidValues as $value) {

            $schedule = new Schedule();

            $schedule->operating_characteristics = $value;

            $this->assertEquals($schedule->operating_characteristics, null, "Fails for invalid string: ".$value);

        }

    }

    public function testSetTrainClassValid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setTrainClassAttribute'),  'Class does not have setTrainClassAttribute method');

        $validValues = ['B', null, 'S'];

        foreach ($validValues as $value) {

            $schedule = new Schedule();

            $schedule->train_class = $value;

            $this->assertEquals($value, $schedule->train_class, "Fails for invalid string: ".$value);

        }

    }

    public function testSetTrainClassInvalid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setTrainClassAttribute'),  'Class does not have setTrainClassAttribute method');

        $invalidValues = ['BS', '1', 'CAT'];

        foreach ($invalidValues as $value) {

            $schedule = new Schedule();

            $schedule->train_class = $value;

            $this->assertEquals($schedule->train_class, null, "Fails for invalid string: ".$value);

        }

    }

    public function testSetSleeperValid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setSleepersAttribute'),  'Class does not have setSleepersAttribute method');

        $validValues = ['B', 'F', 'S', null];

        foreach ($validValues as $value) {

            $schedule = new Schedule();

            $schedule->sleepers = $value;

            $this->assertEquals($value, $schedule->sleepers, "Fails for invalid string: ".$value);

        }

    }

    public function testSetSleeperInvalid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setSleepersAttribute'),  'Class does not have setSleepersAttribute method');

        $invalidValues = ['BS', '1', 'CAT'];

        foreach ($invalidValues as $value) {

            $schedule = new Schedule();

            $schedule->sleepers = $value;

            $this->assertEquals($schedule->sleepers, null, "Fails for invalid string: ".$value);

        }

    }

    public function testSetReservationsValid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setReservationsAttribute'),  'Class does not have setReservationsAttribute method');

        $validValues = ['A', 'E', 'R', 'S', null];

        foreach ($validValues as $value) {

            $schedule = new Schedule();

            $schedule->reservations = $value;

            $this->assertEquals($value, $schedule->reservations, "Fails for invalid string: ".$value);

        }

    }

    public function testSetReservationsInvalid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setReservationsAttribute'),  'Class does not have setReservationsAttribute method');

        $invalidValues = ['BS', '1', 'CAT'];

        foreach ($invalidValues as $value) {

            $schedule = new Schedule();

            $schedule->reservations = $value;

            $this->assertEquals($schedule->reservations, null, "Fails for invalid string: ".$value);

        }

    }

    public function testSetCateringCodeValid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setCateringCodeAttribute'),  'Class does not have setCateringCodeAttribute method');

        $validValues = ['C', 'F', 'H', 'M', 'P', 'R', 'T', 'CH', 'MP', 'RT', null];

        foreach ($validValues as $value) {

            $schedule = new Schedule();

            $schedule->catering_code = $value;

            $this->assertEquals($value, $schedule->catering_code, "Fails for invalid string: ".$value);

        }

    }

    public function testSetCateringCodeInvalid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setCateringCodeAttribute'),  'Class does not have setCateringCodeAttribute method');

        $invalidValues = ['BS', '1', 'CAT'];

        foreach ($invalidValues as $value) {

            $schedule = new Schedule();

            $schedule->catering_code = $value;

            $this->assertEquals($schedule->catering_code, null, "Fails for invalid string: ".$value);

        }

    }

    public function testSetServiceBrandingValid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setServiceBrandingAttribute'),  'Class does not have setServiceBrandingAttribute method');

        $validValues = ['E', null];

        foreach ($validValues as $value) {

            $schedule = new Schedule();

            $schedule->service_branding = $value;

            $this->assertEquals($value, $schedule->service_branding, "Fails for invalid string: ".$value);

        }

    }

    public function testSetServiceBrandingInvalid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setServiceBrandingAttribute'),  'Class does not have setServiceBrandingAttribute method');

        $invalidValues = ['BS', '1', 'CAT'];

        foreach ($invalidValues as $value) {

            $schedule = new Schedule();

            $schedule->service_branding = $value;

            $this->assertEquals($schedule->service_branding, null, "Fails for invalid string: ".$value);

        }

    }

    public function testSetStpIndicatorValid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setStpIndicatorAttribute'),  'Class does not have setStpIndicatorAttribute method');

        $validValues = ['C', 'N', 'O', 'P'];

        foreach ($validValues as $value) {

            $schedule = new Schedule();

            $schedule->stp_indicator = $value;

            $this->assertEquals($value, $schedule->stp_indicator, "Fails for invalid string: ".$value);

        }

    }

    public function testSetStpIndicatorInvalid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setStpIndicatorAttribute'),  'Class does not have setStpIndicatorAttribute method');

        $invalidValues = ['BS', '1', 'CAT'];

        foreach ($invalidValues as $value) {

            $schedule = new Schedule();

            $schedule->stp_indicator = $value;

            $this->assertEquals($schedule->stp_indicator, null, "Fails for invalid string: ".$value);

        }

    }

    public function testSetAtocCodeValid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setAtocCodeAttribute'),  'Class does not have setAtocCodeAttribute method');

        $validValues = ['AR','NT','AW','CC','CS','CH','XC','ZZ','EM','ES','FC','HT','GX','ZZ','GN','TL','GC','LN','GW','LE','HC','HX','IL','LS','LM','LO','LT','LT','LT','ME','LR','TW','NY','SR','SW','SJ','SE','SN','SP','XR','TP','VT','GR','WR'];

        foreach ($validValues as $value) {

            $schedule = new Schedule();

            $schedule->atoc_code = $value;

            $this->assertEquals($value, $schedule->atoc_code, "Fails for invalid string: ".$value);

        }

    }

    public function testSetAtocCodeInvalid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setAtocCodeAttribute'),  'Class does not have setAtocCodeAttribute method');

        $invalidValues = ['BS', '1', 'CAT'];

        foreach ($invalidValues as $value) {

            $schedule = new Schedule();

            $schedule->atoc_code = $value;

            $this->assertEquals($schedule->atoc_code, null, "Fails for invalid string: ".$value);

        }

    }

    public function testSetApplicableTimetableValid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setApplicableTimetableAttribute'),  'Class does not have setApplicableTimetableAttribute method');

        $validValues = ['Y', 'N'];

        foreach ($validValues as $value) {

            $schedule = new Schedule();

            $schedule->applicable_timetable = $value;

            $this->assertEquals($value, $schedule->applicable_timetable, "Fails for invalid string: ".$value);

        }

    }

    public function testSetApplicableTimetableInvalid()
    {
        $schedule = new Schedule();
        $this->assertTrue(method_exists($schedule, 'setApplicableTimetableAttribute'),  'Class does not have setApplicableTimetableAttribute method');

        $invalidValues = ['BS', '1', 'CAT'];

        foreach ($invalidValues as $value) {

            $schedule = new Schedule();

            $schedule->applicable_timetable = $value;

            $this->assertEquals($schedule->applicable_timetable, null, "Fails for invalid string: ".$value);

        }

    }
}
