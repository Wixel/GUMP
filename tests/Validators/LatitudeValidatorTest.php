<?php

namespace Tests\Validators;

use Tests\BaseTestCase;
use GUMP;

class LatitudeValidatorTest extends BaseTestCase
{
    public function testSuccessWhenValidLatitude()
    {
        $this->assertTrue(GUMP::is_valid([
            'lat' => '40.7128'
        ], [
            'lat' => 'latitude'
        ]));
    }

    public function testSuccessWhenValidNegativeLatitude()
    {
        $this->assertTrue(GUMP::is_valid([
            'lat' => '-33.8688'
        ], [
            'lat' => 'latitude'
        ]));
    }

    public function testSuccessWhenValidBoundaryLatitude()
    {
        $this->assertTrue(GUMP::is_valid([
            'lat' => '90'
        ], [
            'lat' => 'latitude'
        ]));

        $this->assertTrue(GUMP::is_valid([
            'lat' => '-90'
        ], [
            'lat' => 'latitude'
        ]));
    }

    public function testErrorWhenLatitudeTooHigh()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'lat' => '91'
        ], [
            'lat' => 'latitude'
        ]));
    }

    public function testErrorWhenLatitudeTooLow()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'lat' => '-91'
        ], [
            'lat' => 'latitude'
        ]));
    }

    public function testErrorWhenNotNumeric()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'lat' => 'not-a-number'
        ], [
            'lat' => 'latitude'
        ]));
    }
}