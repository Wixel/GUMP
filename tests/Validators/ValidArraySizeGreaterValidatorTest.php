<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class ValidArraySizeGreaterValidatorTest
 *
 * @package Tests
 */
class ValidArraySizeGreaterValidatorTest extends BaseTestCase
{
    const RULE = 'valid_array_size_greater,3';

    public function testWhenEqualIsSuccess()
    {
        $this->assertTrue($this->validate(self::RULE, [1, 2, 3]));
    }

    public function testWhenGreaterIsSuccess()
    {
        $this->assertTrue($this->validate(self::RULE, [1, 2, 3, 4]));
    }

    public function testWhenLesserIsFailure()
    {
        $this->assertNotTrue($this->validate(self::RULE, [1, 2]));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate(self::RULE, ''));
    }
}