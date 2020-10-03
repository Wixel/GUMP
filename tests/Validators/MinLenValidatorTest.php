<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;
use Mockery as m;

/**
 * Class MinLenValidatorTest
 *
 * @package Tests
 */
class MinLenValidatorTest extends BaseTestCase
{
    public function testSuccessWhenEqual()
    {
        $this->assertTrue($this->validate('min_len,5', 'ñándú'));
    }

    public function testSuccessWhenMore()
    {
        $this->assertTrue($this->validate('min_len,2', 'ñán'));
    }

    public function testErrorWhenLess()
    {
        $this->assertNotTrue($this->validate('min_len,2', 'ñ'));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate('min_len,2', ''));
    }
}