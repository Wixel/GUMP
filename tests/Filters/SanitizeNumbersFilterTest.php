<?php

namespace Tests\Filters;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class SanitizeNumbersFilterTest
 *
 * @package Tests
 */
class SanitizeNumbersFilterTest extends BaseTestCase
{
    const FILTER = 'sanitize_numbers';

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
            ['1.2a', 12],
            ['-1', '-1'],
            [4.2, 42],
        ];
    }
}