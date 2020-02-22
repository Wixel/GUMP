<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class DoesntContainListValidatorTest
 *
 * @package Tests
 */
class DoesntContainListValidatorTest extends BaseTestCase
{
    public function testSuccess()
    {
        $this->assertTrue($this->validate('doesnt_contain_list,one;two', 'three'));
    }

    public function testFailure()
    {
        $this->assertNotTrue($this->validate('doesnt_contain_list,one;two', 'one'));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
        $this->assertTrue($this->validate('doesnt_contain_list,one;two', ''));
    }
}