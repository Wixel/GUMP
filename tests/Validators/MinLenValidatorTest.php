<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class MinLenValidatorTest
 *
 * @package Tests
 */
class MinLenValidatorTest extends BaseTestCase
{
    public function testSuccessWhenEqual()
    {
        $this->assertTrue($this->validate('min_len,2', '12'));
    }

    public function testSuccessWhenMore()
    {
        $this->assertTrue($this->validate('min_len,2', '123'));
    }

    public function testErrorWhenLess()
    {
        $this->assertNotTrue($this->validate('min_len,2', '1'));
    }
}