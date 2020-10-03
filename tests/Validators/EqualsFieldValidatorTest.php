<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class EqualsFieldValidatorTest
 *
 * @package Tests
 */
class EqualsFieldValidatorTest extends BaseTestCase
{
    public function testWhenEqualSuccess()
    {
        $result = $this->gump->validate([
            'test' => 'string',
            'the_other_field' => 'string'
        ], [
            'test' => 'equalsfield,the_other_field'
        ]);

        $this->assertTrue($result);
    }

    public function testWhenDifferentFails()
    {
        $result = $this->gump->validate([
            'test' => 'string',
            'the_other_field' => 'different_string'
        ], [
            'test' => 'equalsfield,the_other_field'
        ]);

        $this->assertNotTrue($result);
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
        $this->assertTrue($this->validate('equalsfield,the_other_field', ''));
    }
}