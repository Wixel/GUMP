<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class ContainsValidatorTest
 *
 * @package Tests
 */
class ContainsValidatorTest extends BaseTestCase
{
    /**
     * @dataProvider successProvider
     */
    public function testSuccess($rule, $input)
    {
        $this->assertTrue($this->validate($rule, $input));
    }

    public function successProvider()
    {
        return [
            ['contains,one', 'one'],
            ['contains,one;two;with space', 'with space'],
            [['contains' => ['one']], 'one'],
            [['contains' => ['one', 'two']], 'two'],
        ];
    }

    /**
     * @dataProvider errorProvider
     */
    public function testError($rule, $input)
    {
        $this->assertNotTrue($this->validate($rule, $input));
    }

    public function errorProvider()
    {
        return [
            ['contains,one', 'two'],
            ['contains,one;two;with space', 'with spac'],
        ];
    }
}