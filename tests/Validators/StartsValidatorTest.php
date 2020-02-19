<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class StartsValidatorTest
 *
 * @package Tests
 */
class StartsValidatorTest extends BaseTestCase
{
    private const RULE = 'starts,tes';

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
            ['testing'],
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
            ['ttes'],
            ['tst'],
        ];
    }
}