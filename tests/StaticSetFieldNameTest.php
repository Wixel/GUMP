<?php

namespace Tests;

use GUMP;
use Exception;

/**
 * Class StaticSetFieldNameTest
 *
 * @package Tests
 */
class StaticSetFieldNameTest extends BaseTestCase
{
    public function testSetFieldNamesStaticCall()
    {
        $keysValues = [
            'test_number' =>'Test Num.'
        ];

        GUMP::set_field_names($keysValues);

        $this->gump->validate([
            'test_number' => '111'
        ], [
            'test_number' => 'alpha'
        ]);

        $this->assertEquals($keysValues, self::getPrivateField(GUMP::class, 'fields'));
        $this->assertEquals([
            'test_number' => 'The Test Num. field may only contain letters'
        ], $this->gump->get_errors_array());
    }

}