<?php

namespace Tests\Validators;

use Tests\BaseTestCase;
use GUMP;

class UuidValidatorTest extends BaseTestCase
{
    public function testSuccessWhenValidUuidV4()
    {
        $this->assertTrue(GUMP::is_valid([
            'id' => '550e8400-e29b-41d4-a716-446655440000'
        ], [
            'id' => 'uuid'
        ]));
    }

    public function testSuccessWhenValidUuidV1()
    {
        $this->assertTrue(GUMP::is_valid([
            'id' => '6ba7b810-9dad-11d1-80b4-00c04fd430c8'
        ], [
            'id' => 'uuid'
        ]));
    }

    public function testSuccessWhenValidUuidV5()
    {
        $this->assertTrue(GUMP::is_valid([
            'id' => '6ba7b811-9dad-11d1-80b4-00c04fd430c8'
        ], [
            'id' => 'uuid'
        ]));
    }

    public function testErrorWhenInvalidFormat()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'id' => 'invalid-uuid-format'
        ], [
            'id' => 'uuid'
        ]));
    }

    public function testErrorWhenMissingDashes()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'id' => '550e8400e29b41d4a716446655440000'
        ], [
            'id' => 'uuid'
        ]));
    }

    public function testErrorWhenInvalidVersion()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'id' => '550e8400-e29b-61d4-a716-446655440000'
        ], [
            'id' => 'uuid'
        ]));
    }
}