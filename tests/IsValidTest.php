<?php

namespace Tests;

use GUMP;
use Exception;

/**
 * Class IsValidTest
 *
 * @package Tests
 */
class IsValidTest extends BaseTestCase
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
        ]);

        $this->assertEquals([
            'The <span class="gump-field">Test</span> field must be a number'
        ], $result);
    }
}