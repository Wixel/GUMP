<?php

namespace Tests\Validators;

use Tests\BaseTestCase;
use GUMP;

class StrongPasswordValidatorTest extends BaseTestCase
{
    public function testSuccessWhenValidStrongPassword()
    {
        $this->assertTrue(GUMP::is_valid([
            'password' => 'MyStr0ng!Pass'
        ], [
            'password' => 'strong_password'
        ]));
    }

    public function testErrorWhenNoUppercase()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'password' => 'mystr0ng!pass'
        ], [
            'password' => 'strong_password'
        ]));
    }

    public function testErrorWhenNoLowercase()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'password' => 'MYSTR0NG!PASS'
        ], [
            'password' => 'strong_password'
        ]));
    }

    public function testErrorWhenNoNumber()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'password' => 'MyString!Pass'
        ], [
            'password' => 'strong_password'
        ]));
    }

    public function testErrorWhenNoSpecialChar()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'password' => 'MyStr0ngPass'
        ], [
            'password' => 'strong_password'
        ]));
    }

    public function testErrorWhenTooShort()
    {
        $this->assertNotSame(true, GUMP::is_valid([
            'password' => 'MyS0!7'
        ], [
            'password' => 'strong_password'
        ]));
    }
}