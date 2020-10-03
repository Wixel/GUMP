<?php

namespace Tests\Filters;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class BooleanFilterTest
 *
 * @package Tests
 */
class BooleanFilterTest extends BaseTestCase
{
    const FILTER = 'boolean';

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
            ['1', true],
            [1, true],
            [true, true],
            ['yes', true],
            ['on', true],

            ['', false],
            ['no', false],
            [null, false],
            [false, false],
        ];
    }
}