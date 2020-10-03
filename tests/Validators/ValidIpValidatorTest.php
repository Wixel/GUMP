<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class ValidIpValidatorTest
 *
 * @package Tests
 */
class ValidIpValidatorTest extends BaseTestCase
{
    const RULE = 'valid_ip';

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
            [ '2001:0db8:85a3:08d3:1319:8a2e:0370:7334' ],
            [ '127.0.0.1' ],
            [ '255.255.255.255' ],
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
            [ '2001:0zb8:85a3:08d3:1319:8a2e:0370:7334' ],
            [ '0,0,0,0' ],
            [ '256.0.0.0' ],
        ];
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate(self::RULE, ''));
    }
}