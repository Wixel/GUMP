<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class ValidArraySizeLesserValidatorTest
 *
 * @package Tests
 */
class ValidArraySizeLesserValidatorTest extends BaseTestCase
{
    private const RULE = 'valid_array_size_lesser,3';

    public function testWhenEqualIsSuccess()
    {
        $this->assertTrue($this->validate(self::RULE, [1, 2, 3]));
    }

    public function testWhenLesserIsSuccess()
    {
        $this->assertTrue($this->validate(self::RULE, [1, 2]));
    }

    public function testWhenGreaterIsFailure()
    {
        $this->assertNotTrue($this->validate(self::RULE, [1, 2, 3, 4]));
    }
}