<?php

namespace Tests\Filters;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class SlugFilterTest
 *
 * @package Tests
 */
class SlugFilterTest extends BaseTestCase
{
    const FILTER = 'slug';

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
            ['test space.!@`~;:/\\<>', 'test-space'],
            ['test', 'test'],
            ['Test case', 'test-case'],
        ];
    }
}