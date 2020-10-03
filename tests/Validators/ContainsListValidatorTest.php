<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class ContainsListValidatorTest
 *
 * @package Tests
 */
class ContainsListValidatorTest extends BaseTestCase
{
    public function testSuccess()
    {
        $this->assertTrue($this->validate('contains_list,one;two', 'one'));
    }

    public function testFailure()
    {
        $this->assertNotTrue($this->validate('contains_list,one;two', '0'));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
        $this->assertTrue($this->validate('contains_list,one;two', ''));
    }
}