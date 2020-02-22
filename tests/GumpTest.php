<?php

namespace Tests;

use GUMP;
use Exception;

/**
 * Class GetErrorsArrayTest
 *
 * @package Tests
 */
class GumpTest extends BaseTestCase
{
    public function testSetFieldNamesStaticCall()
    {
        $keysValues = [
            'test_number' =>'Test Num.',
            'test_string' => 'Test String'
        ];

        $test = GUMP::set_field_names($keysValues);

        $this->assertEquals($keysValues, self::getPrivateField(GUMP::class, 'fields'));
    }

    public function testSetErrorMessagesStaticCall()
    {
        $keysValues = [
            'numeric' =>'Field should be numeric',
            'min_len' => 'Field length must be higher than what it is now'
        ];

        $test = GUMP::set_error_messages($keysValues);

        $this->assertEquals($keysValues, self::getPrivateField(GUMP::class, 'validation_methods_errors'));
    }

    public function testFieldStaticCallRetrievesValueIfKeyExists()
    {
        $keysValues = [
            'numeric' => 'Field should be numeric',
            'min_len' => 'Field length must be higher than what it is now'
        ];

        $result = GUMP::field('numeric', $keysValues);

        $this->assertEquals('Field should be numeric', $result);
    }

    public function testFieldStaticCallRetrievesDefaultWhenKeyDoesntExist()
    {
        $keysValues = [
            'numeric' => 'Field should be numeric',
            'min_len' => 'Field length must be higher than what it is now'
        ];

        $result = GUMP::field('input_field', $keysValues, 'Default error message');

        $this->assertEquals('Default error message', $result);
    }
}