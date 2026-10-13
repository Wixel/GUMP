<?php

namespace Tests;

use GUMP;

/**
 * Class StaticSetErrorMessagesTest
 *
 * @package Tests
 */
class StaticSetErrorMessagesTest extends BaseTestCase
{
    public function testSetErrorMessagesStaticCall()
    {
        $keysValues = [
            'numeric' => 'Field should be numeric',
            'min_len' => 'Field length must be higher than what it is now',
        ];

        $test = GUMP::set_error_messages($keysValues);

        $this->assertEquals($keysValues, self::getPrivateField($this->gump, 'validation_methods_errors'));
    }

    public function testItOverwritesAddValidatorErrorMessage()
    {
        GUMP::add_validator('custom', function ($field, $input, array $params = []) {
            return $input[$field] === 'ok';
        }, 'Error message');

        GUMP::set_error_messages([
            'custom' => 'Field {field} should be numeric',
        ]);

        $result = GUMP::is_valid([
            'test' => 'notOk',
        ], [
            'test' => 'custom',
        ]);

        $this->assertEquals([
            'Field <span class="gump-field">Test</span> should be numeric',
        ], $result);
    }

    public function testItOverwritesLanguagefileErrorMessage()
    {
        GUMP::set_error_messages([
            'numeric' => 'Field {field} should be numeric !!!!!!!!!',
        ]);

        $result = GUMP::is_valid([
            'test' => 'notOk',
        ], [
            'test' => 'numeric',
        ]);

        $this->assertEquals([
            'Field <span class="gump-field">Test</span> should be numeric !!!!!!!!!',
        ], $result);
    }

    public function testItOverwritesRequiredWithErrorMessage()
    {
        GUMP::set_error_messages([
            'required_with' => 'Custom required_with message',
        ]);

        $result = GUMP::is_valid([
            'shipping_address' => '123 Main St',
        ], [
            'recipient_name' => 'required_with,shipping_address',
        ]);

        $this->assertEquals([
            'Custom required_with message',
        ], $result);
    }

    public function testItOverwritesRequiredWithAllErrorMessage()
    {
        GUMP::set_error_messages([
            'required_with_all' => 'Custom required_with_all message',
        ]);

        $result = GUMP::is_valid([
            'shipping_address' => '123 Main St',
            'billing_address' => '456 Elm St',
            'order_note' => '',
        ], [
            'order_note' => 'required_with_all,shipping_address;billing_address',
        ]);

        $this->assertEquals([
            'Custom required_with_all message',
        ], $result);
    }

    public function testItOverwritesRequiredWithoutErrorMessage()
    {
        GUMP::set_error_messages([
            'required_without' => 'Custom required_without message',
        ]);

        $result = GUMP::is_valid([
            'phone' => '',
            'mobile' => '',
        ], [
            'contact_email' => 'required_without,phone;mobile',
        ]);

        $this->assertEquals([
            'Custom required_without message',
        ], $result);
    }

    public function testItOverwritesRequiredWithoutAllErrorMessage()
    {
        GUMP::set_error_messages([
            'required_without_all' => 'Custom required_without_all message',
        ]);

        $result = GUMP::is_valid([
            'phone' => '',
            'mobile' => '',
        ], [
            'contact_email' => 'required_without_all,phone;mobile',
        ]);

        $this->assertEquals([
            'Custom required_without_all message',
        ], $result);
    }

    public function testItOverwritesRequiredArrayKeysErrorMessage()
    {
        GUMP::set_error_messages([
            'required_array_keys' => 'Custom required_array_keys message',
        ]);

        $result = GUMP::is_valid([
            'data' => ['id' => 10],
        ], [
            'data' => 'required_array_keys,id;name',
        ]);

        $this->assertEquals([
            'Custom required_array_keys message',
        ], $result);
    }
}
