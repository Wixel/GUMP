<?php

namespace Tests\Validators;

use Tests\BaseTestCase;

class RequiredArrayKeysValidatorTest extends BaseTestCase
{
    public function testSuccessWhenArrayContainsAllRequiredKeys()
    {
        $result = $this->gump->validate(
            [
                'data' => ['id' => 10, 'name' => 'Order 42'],
            ],
            [
                'data' => 'required_array_keys,id;name',
            ]
        );

        $this->assertTrue($result);
    }

    public function testErrorWhenFieldIsNotAnArray()
    {
        $result = $this->gump->validate(
            [
                'data' => 'not-an-array',
            ],
            [
                'data' => 'required_array_keys,id;name',
            ]
        );

        $this->assertNotTrue($result);
    }

    public function testErrorWhenOneRequiredKeyIsMissing()
    {
        $result = $this->gump->validate(
            [
                'data' => ['id' => 10],
            ],
            [
                'data' => 'required_array_keys,id;name',
            ]
        );

        $this->assertNotTrue($result);
    }
}

