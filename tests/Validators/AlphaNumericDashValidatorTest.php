<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class AlphaNumericDashValidatorTest
 *
 * @package Tests
 */
class AlphaNumericDashValidatorTest extends BaseTestCase
{
    public function testSuccess()
    {
        $this->assertTrue($this->validate('alpha_numeric_dash', 'My_username-with_dash123'));
    }

    public function testError()
    {
        $this->assertNotTrue($this->validate('alpha_numeric_dash', 'hello *(^*^*&'));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate('alpha_numeric_dash', ''));
    }
}