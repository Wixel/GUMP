<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class AlphaNumericSpaceValidatorTest
 *
 * @package Tests
 */
class AlphaNumericSpaceValidatorTest extends BaseTestCase
{
    public function testSuccess()
    {
        $this->assertTrue($this->validate('alpha_numeric_space', 'my username123'));
    }

    public function testError()
    {
        $this->assertNotTrue($this->validate('alpha_numeric_space', 'hello *(^*^*&'));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate('alpha_numeric_space', ''));
    }
}