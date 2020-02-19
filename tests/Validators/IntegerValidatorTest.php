<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class IntegerValidatorTest
 *
 * @package Tests
 */
class IntegerValidatorTest extends BaseTestCase
{
    public function testSuccess()
    {
        $this->assertTrue($this->validate('integer', '123'));
        $this->assertTrue($this->validate('integer', 123));
        $this->assertTrue($this->validate('integer', -1));
        $this->assertTrue($this->validate('integer', 0));
        $this->assertTrue($this->validate('integer', '0'));
    }

    public function testError()
    {
        $this->assertNotTrue($this->validate('integer', 1.1));
    }
}