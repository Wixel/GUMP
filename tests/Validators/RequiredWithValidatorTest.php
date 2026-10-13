<?php

namespace Tests\Validators;

use Tests\BaseTestCase;

class RequiredWithValidatorTest extends BaseTestCase
{
    public function testSuccessWhenTriggerIsPresentAndFieldIsFilled()
    {
        $result = $this->gump->validate(
            [
                'shipping_address' => '123 Main St',
                'recipient_name' => 'John Doe',
            ],
            [
                'recipient_name' => 'required_with,shipping_address',
            ]
        );

        $this->assertTrue($result);
    }

    public function testSuccessWhenTriggerIsNotPresent()
    {
        $result = $this->gump->validate(
            [
                'shipping_address' => '',
            ],
            [
                'recipient_name' => 'required_with,shipping_address',
            ]
        );

        $this->assertTrue($result);
    }

    public function testErrorWhenTriggerIsPresentAndFieldIsMissing()
    {
        $result = $this->gump->validate(
            [
                'shipping_address' => '123 Main St',
            ],
            [
                'recipient_name' => 'required_with,shipping_address',
            ]
        );

        $this->assertEquals([
            [
                'field' => 'recipient_name',
                'value' => null,
                'rule' => 'required_with',
                'params' => ['shipping_address'],
            ]
        ], $result);
    }
}

