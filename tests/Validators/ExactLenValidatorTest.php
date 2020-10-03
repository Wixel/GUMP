<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;
use Mockery as m;

/**
 * Class ExactLenValidatorTest
 *
 * @package Tests
 */
class ExactLenValidatorTest extends BaseTestCase
{
    public function testSuccessWhenEqual()
    {
        $this->assertTrue($this->validate('exact_len,5', 'ñándú'));
    }

    public function testErrorWhenMore()
    {
        $this->assertNotTrue($this->validate('exact_len,2', 'ñán'));
    }

    public function testErrorWhenLess()
    {
        $this->assertNotTrue($this->validate('exact_len,2', 'ñ'));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate('exact_len,2', ''));
    }
}