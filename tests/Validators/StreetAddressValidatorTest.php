<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class StreetAddressValidatorTest
 *
 * @package Tests
 */
class StreetAddressValidatorTest extends BaseTestCase
{
    const RULE = 'street_address';

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
            ['6 Avondans Road'],
            ['Calle Mediterráneo 2'],
            ['c/Mediterráneo 2'],
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
            [ 'Avondans Road' ],
            [ 'text' ],
        ];
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate(self::RULE, ''));
    }
}