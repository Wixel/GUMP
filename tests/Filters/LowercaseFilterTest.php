<?php

namespace Tests\Filters;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class LowerFilterTest
 *
 * @package Tests
 */
class LowerFilterTest extends BaseTestCase
{
    const FILTER = 'lower_case';

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
            ['Hello', 'hello']
        ];
    }
}