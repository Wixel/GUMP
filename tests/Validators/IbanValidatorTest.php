<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class IbanValidatorTest
 *
 * @package Tests
 */
class IbanValidatorTest extends BaseTestCase
{
    const RULE = 'iban';

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
            ['FR7630006000011234567890189'],
            ['ES7921000813610123456789'],
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
            ['FR7630006000011234567890181'],
            ['E7921000813610123456789'],
            ['text'],
        ];
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
        $this->assertTrue($this->validate(self::RULE, ''));
    }
}