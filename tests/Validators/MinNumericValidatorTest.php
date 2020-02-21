<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class MinNumericValidatorTest
 *
 * @package Tests
 */
class MinNumericValidatorTest extends BaseTestCase
{
    public function testSuccessWhenEqual()
    {
        $this->assertTrue($this->validate('min_numeric,2', 2));
    }

    public function testSuccessWhenMore()
    {
        $this->assertTrue($this->validate('min_numeric,2', 3));
    }

    public function testErrorWhenLess()
    {
        $this->assertNotTrue($this->validate('min_numeric,2', 1));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate('min_numeric,2', ''));
    }
}