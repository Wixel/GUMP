<?php

namespace Tests\Validators;

use Tests\BaseTestCase;
use GUMP;

class JwtTokenValidatorTest extends BaseTestCase
{
    public function testSuccessWhenValidJwtToken()
    {
        $this->assertTrue(GUMP::is_valid([
            'token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c'
        ], [
            'token' => 'jwt_token'
        ]));
    }

    public function testErrorWhenInvalidFormat()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'token' => 'invalid.token'
        ], [
            'token' => 'jwt_token'
        ]));
    }

    public function testErrorWhenTooManyParts()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'token' => 'part1.part2.part3.part4'
        ], [
            'token' => 'jwt_token'
        ]));
    }

    public function testErrorWhenInvalidCharacters()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'token' => 'invalid!.characters#.here$'
        ], [
            'token' => 'jwt_token'
        ]));
    }
}