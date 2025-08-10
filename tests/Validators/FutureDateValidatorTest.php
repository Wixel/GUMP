<?php

namespace Tests\Validators;

use Tests\BaseTestCase;
use GUMP;

class FutureDateValidatorTest extends BaseTestCase
{
    public function testSuccessWhenValidFutureDate()
    {
        $futureDate = date('Y-m-d', strtotime('+1 day'));
        $this->assertTrue(GUMP::is_valid([
            'date' => $futureDate
        ], [
            'date' => 'future_date'
        ]));
    }

    public function testSuccessWhenValidFutureDateTime()
    {
        $futureDateTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $this->assertTrue(GUMP::is_valid([
            'date' => $futureDateTime
        ], [
            'date' => 'future_date'
        ]));
    }

    public function testErrorWhenPastDate()
    {
        $pastDate = date('Y-m-d', strtotime('-1 day'));
        $this->assertNotSame(true, GUMP::is_valid([
            'date' => $pastDate
        ], [
            'date' => 'future_date'
        ]));
    }

    public function testErrorWhenCurrentDate()
    {
        $currentDate = date('Y-m-d');
        $this->assertNotSame(true, GUMP::is_valid([
            'date' => $currentDate
        ], [
            'date' => 'future_date'
        ]));
    }

    public function testErrorWhenInvalidDate()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'date' => 'invalid-date'
        ], [
            'date' => 'future_date'
        ]));
    }
}