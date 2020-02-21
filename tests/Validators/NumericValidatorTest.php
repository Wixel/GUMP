<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class NumericValidatorTest
 *
 * @package Tests
 */
class NumericValidatorTest extends BaseTestCase
{
    public function testSuccess()
    {
        $this->assertTrue($this->validate('numeric', '123'));
        $this->assertTrue($this->validate('numeric', 123));
        $this->assertTrue($this->validate('numeric', 1.2));
        $this->assertTrue($this->validate('numeric', 0));
        $this->assertTrue($this->validate('numeric', '0'));
        $this->assertTrue($this->validate('numeric', -1));
        $this->assertTrue($this->validate('numeric', '-1'));
    }

    public function testError()
    {
        $this->assertNotTrue($this->validate('numeric', 'n0t'));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate('numeric', ''));
    }
}