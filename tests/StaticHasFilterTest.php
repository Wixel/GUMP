<?php

namespace Tests;

use GUMP;

class StaticHasFilterTest extends BaseTestCase
{
    public function dataOfExistingRules()
    {
        return [
            // There are native filters
            ['noise_words'],
            ['upper_case'],
            ['slug'],
            // These are built-in functions
            ['trim'],
            ['strtoupper'],
        ];
    }

    /**
     * @dataProvider dataOfExistingRules
     */
    public function testHasFilterWhenExists(string $rule)
    {
        $this->assertTrue(GUMP::has_filter($rule));
    }

    public function testHasFilterWithCustomRule(): void
    {
        GUMP::add_filter('test', function($value, array $params = []) {
            return strtoupper($value);
        });

        $this->assertTrue(GUMP::has_filter('test'));
    }

    public function testHasFilterWhenNotExists(): void
    {
        $this->assertFalse(GUMP::has_filter('test'));
    }
}
