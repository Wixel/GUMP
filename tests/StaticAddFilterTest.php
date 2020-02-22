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
        GUMP::add_filter("custom", function($value, $params = NULL) {
            return strtoupper($value);
        });

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Filter rule 'custom' already exists.");

        GUMP::add_filter("custom", function($value, $params = NULL) {
            return strtoupper($value);
        });
    }
}