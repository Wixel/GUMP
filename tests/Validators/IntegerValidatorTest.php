<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class IntegerValidatorTest
 *
 * @package Tests
 */
class IntegerValidatorTest extends BaseTestCase
{
    const RULE = 'required|integer';

    /**
     * @dataProvider successProvider
     */
    public function testSuccess($input)
    {
        $this->assertTrue($this->validate(self::RULE, $input));
    }

    public function successProvider()
    {
        return [
            ['123'],
            [123],
            [-1],
            [0],
            ['0'],
        ];
    }

    /**
     * @dataProvider errorProvider
     */
    public function testError($input)
    {
        $this->assertNotTrue($this->validate(self::RULE, $input));
    }

    public function errorProvider()
    {
        return [
            ['text'],
            [true],
            [null],
            [1.1],
            ['1.1'],
            [['array']],
        ];
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate('integer', ''));
    }
}