<?php

namespace Tests\Validators;

use Tests\BaseTestCase;

class RequiredWithAllValidatorTest extends BaseTestCase
{
    public function testSuccessWhenAllTriggersArePresentAndFieldIsFilled()
    {
        $result = $this->gump->validate(
            [
                'shipping_address' => '123 Main St',
                'billing_address' => '456 Elm St',
                'order_note' => 'Send ASAP',
            ],
            [
                'order_note' => 'required_with_all,shipping_address;billing_address',
            ]
        );

        $this->assertTrue($result);
    }

    public function testSuccessWhenNotAllTriggersArePresent()
    {
        $result = $this->gump->validate(
            [
                'shipping_address' => '123 Main St',
                'billing_address' => '',
            ],
            [
                'order_note' => 'required_with_all,shipping_address;billing_address',
            ]
        );

        $this->assertTrue($result);
    }

    public function testErrorWhenAllTriggersArePresentAndFieldIsEmpty()
    {
        $result = $this->gump->validate(
            [
                'shipping_address' => '123 Main St',
                'billing_address' => '456 Elm St',
                'order_note' => '',
            ],
            [
                'order_note' => 'required_with_all,shipping_address;billing_address',
            ]
        );

        $this->assertEquals([
            [
                'field' => 'order_note',
                'value' => '',
                'rule' => 'required_with_all',
                'params' => ['shipping_address', 'billing_address'],
            ]
        ], $result);
    }
}

