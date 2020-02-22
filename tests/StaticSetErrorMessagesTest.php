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

    public function testSetErrorMessageAlsoAppliesForCustomValidators()
    {
        GUMP::add_validator("custom", function($field, $input, $param = NULL) {
            return $input[$field] === 'ok';
        });

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
}