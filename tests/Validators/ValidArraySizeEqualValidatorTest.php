<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class ValidArraySizeEqualValidatorTest
 *
 * @package Tests
 */
class ValidArraySizeEqualValidatorTest extends BaseTestCase
{
    private const RULE = 'valid_array_size_equal,3';

    public function testWhenEqualIsSuccess()
    {
        $this->assertTrue($this->validate(self::RULE, [1, 2, 3]));
    }

    public function testWhenGreaterIsFailure()
    {
        $this->assertNotTrue($this->validate(self::RULE, [1, 2, 3, 4]));
    }

    public function testWhenLesserIsFailure()
    {
        $this->assertNotTrue($this->validate(self::RULE, [1, 2]));
    }
}