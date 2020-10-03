<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class Guidv4ValidatorTest
 *
 * @package Tests
 */
class Guidv4ValidatorTest extends BaseTestCase
{
    const RULE = 'guidv4';

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
            ['A98C5A1E-A742-4808-96FA-6F409E799937'],
            ['7deca41a-3479-4f18-9771-3531f742061b'],
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
            ['A98C5A1EA742480896FA6F409E799937'],
            ['7deca41a-9771-3531f742061b'],
        ];
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
        $this->assertTrue($this->validate(self::RULE, ''));
    }
}