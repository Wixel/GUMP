<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class PhoneNumberValidatorTest
 *
 * @package Tests
 */
class PhoneNumberValidatorTest extends BaseTestCase
{
    const RULE = 'phone_number';

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
            ['555-555-5555'],
            ['5555425555'],
            ['555 555 5555'],
            ['1(519) 555-4444'],
            ['1 (519) 555-4422'],
            ['1-555-555-5555'],
            ['1-(555)-555-5555'],
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
            ['666111222'],
            ['004461234123'],
        ];
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate(self::RULE, ''));
    }
}