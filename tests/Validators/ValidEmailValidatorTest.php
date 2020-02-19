<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class ValidEmailValidatorTest
 *
 * @package Tests
 */
class ValidEmailValidatorTest extends BaseTestCase
{
    public function testSuccess()
    {
        $this->assertTrue($this->validate('valid_email', 'myemail@host.com'));
    }

    public function testFailure()
    {
        $this->assertNotTrue($this->validate('valid_email', 's0meth1ng-notEmail\r'));
    }
}