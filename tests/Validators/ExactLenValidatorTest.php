<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class ExactLenValidatorTest
 *
 * @package Tests
 */
class ExactLenValidatorTest extends BaseTestCase
{
    public function testSuccessWhenEqual()
    {
        $this->assertTrue($this->validate('exact_len,2', '12'));
    }

    public function testErrorWhenMore()
    {
        $this->assertNotTrue($this->validate('exact_len,2', '123'));
    }

    public function testErrorWhenLess()
    {
        $this->assertNotTrue($this->validate('exact_len,2', '1'));
    }
}