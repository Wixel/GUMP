<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class MaxLenValidatorTest
 *
 * @package Tests
 */
class MaxLenValidatorTest extends BaseTestCase
{
    public function testSuccessWhenEqual()
    {
        $this->assertTrue($this->validate('max_len,2', '12'));
    }

    public function testSuccessWhenLess()
    {
        $this->assertTrue($this->validate('max_len,2', '1'));
    }

    public function testErrorWhenMore()
    {
        $this->assertNotTrue($this->validate('max_len,2', '123'));
    }
}