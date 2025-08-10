<?php

namespace Tests\Validators;

use Tests\BaseTestCase;
use GUMP;

class MacAddressValidatorTest extends BaseTestCase
{
    public function testSuccessWhenValidMacWithColons()
    {
        $this->assertTrue(GUMP::is_valid([
            'mac' => '00:11:22:33:44:55'
        ], [
            'mac' => 'mac_address'
        ]));
    }

    public function testSuccessWhenValidMacWithDashes()
    {
        $this->assertTrue(GUMP::is_valid([
            'mac' => '00-11-22-33-44-55'
        ], [
            'mac' => 'mac_address'
        ]));
    }

    public function testSuccessWhenValidMacMixedCase()
    {
        $this->assertTrue(GUMP::is_valid([
            'mac' => 'aB:cD:eF:12:34:56'
        ], [
            'mac' => 'mac_address'
        ]));
    }

    public function testErrorWhenInvalidFormat()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'mac' => '00112233445'
        ], [
            'mac' => 'mac_address'
        ]));
    }

    public function testErrorWhenInvalidCharacters()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'mac' => '00:11:22:33:44:GG'
        ], [
            'mac' => 'mac_address'
        ]));
    }

    public function testErrorWhenWrongLength()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'mac' => '00:11:22:33:44'
        ], [
            'mac' => 'mac_address'
        ]));
    }
}