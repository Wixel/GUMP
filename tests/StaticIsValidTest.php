<?php

namespace Tests;

use GUMP;
use Exception;

/**
 * Class StaticIsValidTest
 *
 * @package Tests
 */
class StaticIsValidTest extends BaseTestCase
{
    public function testOnSuccessReturnsTrue()
    {
        $result = GUMP::is_valid([
            'test' => '123'
        ], [
            'test' => 'numeric'
        ]);

        $this->assertTrue($result);
    }

    public function testOnFailureReturnsArrayWithErrors()
    {
        $result = GUMP::is_valid([
            'test' => 'asd'
        ], [
            'test' => 'numeric'
        ], [
            'test' => ['numeric' => '{field} must be a number please !!!']
        ]);

        $this->assertEquals([
            '<span class="gump-field">Test</span> must be a number please !!!'
        ], $result);
    }
}