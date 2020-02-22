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
}