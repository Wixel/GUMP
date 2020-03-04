<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class ValidUrlValidatorTest
 *
 * @package Tests
 */
class ValidUrlValidatorTest extends BaseTestCase
{
    const RULE = 'valid_url';

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
            ['http://test.com/'],
            ['http://test.com'],
            ['https://test.com'],
            ['tcp://test.com'],
            ['ftp://test.com'],
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
            [ 'example.com' ],
            [ 'text' ]
        ];
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate(self::RULE, ''));
    }
}