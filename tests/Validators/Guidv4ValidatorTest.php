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
    private const RULE = 'guidv4';

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
            ['42878fd4-94fc-42a5-8d5e-6b2377a10b0d'],
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
            ['666111222'],
            ['004461234123'],
        ];
    }
}