<?php

namespace Tests;

use GUMP;

class StaticHasValidatorTest extends BaseTestCase
{
    public function dataOfExistingRules()
    {
        return [
            ['required'],
            ['contains'],
            ['regex'],
            ['valid_array_size_equal'],
        ];
    }

    /**
     * @dataProvider dataOfExistingRules
     */
    public function testHasValidatorWhenExists(string $rule)
    {
        $this->assertTrue(GUMP::has_validator($rule));
    }

    public function testHasValidatorWithCustomRule()
    {
        GUMP::add_validator("equals_string", function($field, array $input, array $params, $value) {
            return $value === $params;
        }, 'Field {field} does not equal to {param}.');

        $this->assertTrue(GUMP::has_validator('equals_string'));
    }

    public function testHasValidatorWhenDoesntExist()
    {
        $this->assertFalse(GUMP::has_validator('equals_string'));
    }
}
