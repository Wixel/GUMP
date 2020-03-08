<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class BooleanValidatorTest
 *
 * @package Tests
 */
class BooleanValidatorTest extends BaseTestCase
{
    const RULE = 'boolean';

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
            [ '1' ],
            ['true'],
            [ true ],
            [1],
            ['0'],
            ['false'],
            [false],
            [0],
            ['yes'],
            ['no'],
            ['on'],
            ['off']
        ];
    }

    /**
     * @dataProvider successProviderForStrictMode
     */
    public function testSuccessStrictMode($input)
    {
        $this->assertTrue($this->validate(self::RULE.',strict', $input));
    }

    public function successProviderForStrictMode()
    {
        return [
            [ true ],
            [ false ],
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
            [ 'randomString' ],
            [ 111 ],
            [ 'TRUE' ],
            [ 'False' ]
        ];
    }

    /**
     * @dataProvider errorProviderForStrictMode
     */
    public function testErrorStrictMode($input)
    {
        $this->assertNotTrue($this->validate(self::RULE.',strict', $input));
    }

    public function errorProviderForStrictMode()
    {
        return [
            [ 'true' ],
            [ 'false' ],
            [ 'yes' ],
            [ 'no' ],
            [ 1 ],
            [ 0 ],
        ];
    }
}