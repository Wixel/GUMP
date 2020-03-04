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
    const RULE = 'valid_email';

    public function testSuccess()
    {
        $this->assertTrue($this->validate(self::RULE, 'myemail@host.com'));
    }

    public function testFailure()
    {
        $this->assertNotTrue($this->validate(self::RULE, 's0meth1ng-notEmail\r'));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate(self::RULE, ''));
    }
}