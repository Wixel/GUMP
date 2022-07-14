<?php

namespace Tests\Filters;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class SanitizeStringFilterTest
 *
 * @package Tests
 */
class SanitizeStringFilterTest extends BaseTestCase
{
    const FILTER = 'sanitize_string';

    /**
     * @dataProvider successProvider
     */
    public function testSuccess($input, $expected)
    {
        $result = $this->filter(self::FILTER, $input);

        $this->assertEquals($expected, $result);
    }

    public function successProvider()
    {
        return [
            ['<Hello World!>', ''],
            ['Hello World!</h1>', 'Hello World!'],
            ['<h1>Hello World!', 'Hello World!'],
            ['<h1>Hello World!</h1>', 'Hello World!'],
            ['Hello World! <some-tag>inside tag</some-tag>', 'Hello World! inside tag'],
        ];
    }
}
