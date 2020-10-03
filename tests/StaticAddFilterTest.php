<?php

namespace Tests;

use GUMP;
use Exception;

/**
 * Class StaticAddFilterTest
 *
 * @package Tests
 */
class StaticAddFilterTest extends BaseTestCase
{
    public function testItThrowsExceptionWhenFilterWithSameNameIsAdded()
    {
        GUMP::add_filter("custom", function($value, array $params = []) {
            return strtoupper($value);
        });

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("'custom' filter is already defined.");

        GUMP::add_filter("custom", function($value, array $params = []) {
            return strtoupper($value);
        });
    }
}