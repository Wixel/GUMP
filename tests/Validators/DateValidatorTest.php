<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class DateValidatorTest
 *
 * @package Tests
 */
class DateValidatorTest extends BaseTestCase
{
    const RULE = 'date';

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
            ['2019-01-21'],
            ['2019-01-21 21:15:11'],
            ['2019-01-21 21:15:11'],
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
            [ '2019-13-21' ],
            [ '2019-02-29' ],
            [ '700-12-01' ],
        ];
    }

    public function testSuccessWithParameters()
    {
        $this->assertTrue($this->validate('date,d-m-Y', '31-12-2019'));
        $this->assertTrue($this->validate('date,d-m-Y H:i', '31-12-2019 10:10'));
    }

    public function testFailureWithParameters()
    {
        $this->assertNotTrue($this->validate('date,d-m-Y', '32-12-2019'));
        $this->assertNotTrue($this->validate('date,d-m-Y H:i', '31-12-2019 10:70'));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
        $this->assertTrue($this->validate(self::RULE, ''));
    }
}