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
    public function testSetFieldNames()
    {
        $keysValues = [
            'test_number' =>'Test Num.',
            'test_string' => 'Test String'
        ];

        $test = GUMP::set_field_names($keysValues);

        $this->assertEquals($keysValues, self::getPrivateField(GUMP::class, 'fields'));
    }

    public function testSetErrorMessages()
    {
        $keysValues = [
            'numeric' =>'Field should be numeric',
            'min_len' => 'Field length must be higher than what it is now'
        ];

        $test = GUMP::set_error_messages($keysValues);

        $this->assertEquals($keysValues, self::getPrivateField(GUMP::class, 'validation_methods_errors'));
    }

}