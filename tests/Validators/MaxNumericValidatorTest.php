<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class MaxNumericValidatorTest
 *
 * @package Tests
 */
class MaxNumericValidatorTest extends BaseTestCase
{
    public function testSuccessWhenEqual()
    {
        $this->assertTrue($this->validate('max_numeric,2', 2));
    }

    public function testSuccessWhenLess()
    {
        $this->assertTrue($this->validate('max_numeric,2', 1));
    }

    public function testErrorWhenMore()
    {
        $this->assertNotTrue($this->validate('max_numeric,2', 3));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate('max_numeric,2', ''));
    }
}