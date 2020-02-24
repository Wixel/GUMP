<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class RequiredValidatorTest
 *
 * @package Tests
 */
class RequiredValidatorTest extends BaseTestCase
{
    private const RULE = 'required';
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
            ['test'],
            ['0'],
            [0.0],
            [0],
            [false],
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
            [''],
            [null],
        ];
    }

    public function testItFailsWhenFieldIsNotPresent()
    {
        $result = $this->gump->validate([], [
            'test' => self::RULE
        ]);

        $this->assertNotTrue($result);
    }
}