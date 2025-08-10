<?php

namespace Tests\Validators;

use Tests\BaseTestCase;
use GUMP;

class HexColorValidatorTest extends BaseTestCase
{
    public function testSuccessWhenValidSixDigitColor()
    {
        $this->assertTrue(GUMP::is_valid([
            'color' => '#FF0000'
        ], [
            'color' => 'hex_color'
        ]));
    }

    public function testSuccessWhenValidThreeDigitColor()
    {
        $this->assertTrue(GUMP::is_valid([
            'color' => '#F0A'
        ], [
            'color' => 'hex_color'
        ]));
    }

    public function testSuccessWhenValidLowercaseColor()
    {
        $this->assertTrue(GUMP::is_valid([
            'color' => '#ff0000'
        ], [
            'color' => 'hex_color'
        ]));
    }

    public function testErrorWhenMissingHash()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'color' => 'FF0000'
        ], [
            'color' => 'hex_color'
        ]));
    }

    public function testErrorWhenInvalidLength()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'color' => '#FF00'
        ], [
            'color' => 'hex_color'
        ]));
    }

    public function testErrorWhenInvalidCharacters()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'color' => '#GG0000'
        ], [
            'color' => 'hex_color'
        ]));
    }
}