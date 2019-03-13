<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Association;

class AssociationTest extends TestCase
{
    public function testSetCategoryValid()
    {
        $association = new Association();
        $this->assertTrue(method_exists($association, 'setCategoryAttribute'),  'Class does not have setCategoryAttribute method');

        $validValues = ['JJ', 'VV', 'NP'];

        foreach ($validValues as $value) {

            $association = new Association();

            $association->category = $value;

            $this->assertEquals($value, $association->category, "Fails for invalid string: ".$value);

        }

    }

    public function testSetCategoryNull()
    {
        $association = new Association();
        $this->assertTrue(method_exists($association, 'setCategoryAttribute'),  'Class does not have setCategoryAttribute method');

        $validValues = ['   ', '  ', ''];

        foreach ($validValues as $value) {

            $association = new Association();

            $association->category = $value;

            $this->assertEquals(null, $association->category, "Fails for invalid string: ".$value);

        }

    }

    public function testSetCategoryInvalid()
    {
        $association = new Association();
        $this->assertTrue(method_exists($association, 'setCategoryAttribute'),  'Class does not have setCategoryAttribute method');

        $invalidValues = ['BS', '1', 'CAT'];

        foreach ($invalidValues as $value) {

            $association = new Association();

            $association->category = $value;

            $this->assertEquals($association->category, null, "Fails for invalid string: ".$value);

        }

    }

    public function testSetDateIndicatorValid()
    {
        $association = new Association();
        $this->assertTrue(method_exists($association, 'setDateIndicatorAttribute'),  'Class does not have setDateIndicatorAttribute method');

        $validValues = ['S', 'N', 'P'];

        foreach ($validValues as $value) {

            $association = new Association();

            $association->date_indicator = $value;

            $this->assertEquals($value, $association->date_indicator, "Fails for invalid string: ".$value);

        }

    }

    public function testSetDateIndicatorNull()
    {
        $association = new Association();
        $this->assertTrue(method_exists($association, 'setDateIndicatorAttribute'),  'Class does not have setDateIndicatorAttribute method');

        $validValues = ['', ' ', '  '];

        foreach ($validValues as $value) {

            $association = new Association();

            $association->date_indicator = $value;

            $this->assertEquals(null, $association->date_indicator, "Fails for invalid string: ".$value);

        }

    }

    public function testSetDateIndicatorInvalid()
    {
        $association = new Association();
        $this->assertTrue(method_exists($association, 'setDateIndicatorAttribute'),  'Class does not have setDateIndicatorAttribute method');

        $invalidValues = ['BS', '1', 'CAT'];

        foreach ($invalidValues as $value) {

            $association = new Association();

            $association->date_indicator = $value;

            $this->assertEquals($association->date_indicator, null, "Fails for invalid string: ".$value);

        }

    }

    public function testSetStpIndicatorValid()
    {
        $association = new Association();
        $this->assertTrue(method_exists($association, 'setStpIndicatorAttribute'),  'Class does not have setStpIndicatorAttribute method');

        $validValues = ['C', 'N', 'O', 'P'];

        foreach ($validValues as $value) {

            $association = new Association();

            $association->stp_indicator = $value;

            $this->assertEquals($value, $association->stp_indicator, "Fails for invalid string: ".$value);

        }

    }

    public function testSetStpIndicatorInvalid()
    {
        $association = new Association();
        $this->assertTrue(method_exists($association, 'setStpIndicatorAttribute'),  'Class does not have setStpIndicatorAttribute method');

        $invalidValues = ['BS', '1', 'CAT'];

        foreach ($invalidValues as $value) {

            $association = new Association();

            $association->stp_indicator = $value;

            $this->assertEquals($association->stp_indicator, null, "Fails for invalid string: ".$value);

        }

    }

}
