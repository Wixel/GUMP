<?php

namespace Tests;

use GUMP;
use Exception;

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
            'numeric' =>'Field should be numeric',
            'min_len' => 'Field length must be higher than what it is now'
        ];

        $test = GUMP::set_error_messages($keysValues);

        $this->assertEquals($keysValues, self::getPrivateField(GUMP::class, 'validation_methods_errors'));
    }

    public function testItOverwritesAddValidatorErrorMessage()
    {
        GUMP::add_validator('custom', function($field, $input, array $params = []) {
            return $input[$field] === 'ok';
        }, 'Error message');

        GUMP::set_error_messages([
            'custom' =>'Field {field} should be numeric'
        ]);

        $result = GUMP::is_valid([
            'test' => 'notOk'
        ], [
            'test' => 'custom'
        ]);

        $this->assertEquals([
            'Field <span class="gump-field">Test</span> should be numeric'
        ], $result);
    }

    public function testItOverwritesLanguagefileErrorMessage()
    {
        GUMP::set_error_messages([
            'numeric' =>'Field {field} should be numeric !!!!!!!!!'
        ]);

        $result = GUMP::is_valid([
            'test' => 'notOk'
        ], [
            'test' => 'numeric'
        ]);

        $this->assertEquals([
            'Field <span class="gump-field">Test</span> should be numeric !!!!!!!!!'
        ], $result);
    }
}