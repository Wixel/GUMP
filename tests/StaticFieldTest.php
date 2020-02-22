<?php

namespace Tests;

use GUMP;
use Exception;

/**
 * Class StaticFieldTest
 *
 * @package Tests
 */
class StaticFieldTest extends BaseTestCase
{

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