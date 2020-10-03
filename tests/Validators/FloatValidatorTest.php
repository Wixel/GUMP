<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class FloatValidatorTest
 *
 * @package Tests
 */
class FloatValidatorTest extends BaseTestCase
{
    const RULE = 'float';

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
            [0],
            [1.1],
            [ '1.1' ],
            [-1.1],
            ['-1.1']
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
            [ '1,1' ],
            [ '1.0,0' ],
            [ '1,0.0' ],
            [ 'text' ]
        ];
    }
}