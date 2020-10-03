<?php

namespace Tests\Filters;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class SanitizeFloatsFilterTest
 *
 * @package Tests
 */
class SanitizeFloatsFilterTest extends BaseTestCase
{
    const FILTER = 'sanitize_floats';

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
            [1, 1],
            ['1.2a', '1.2'],
            ['-1', '-1'],
            [4.2, 4.2],
        ];
    }
}