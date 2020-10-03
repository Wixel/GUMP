<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class AlphaDashValidatorTest
 *
 * @package Tests
 */
class AlphaDashValidatorTest extends BaseTestCase
{
    public function testSuccess()
    {
        $this->assertTrue($this->validate('alpha_dash', 'my_username-with_dash'));
    }

    public function testError()
    {
        $this->assertNotTrue($this->validate('alpha_dash', 'hello123'));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate('alpha_dash', ''));
    }
}