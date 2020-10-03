<?php

namespace Tests\Filters;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class BasicTagsFilterTest
 *
 * @package Tests
 */
class BasicTagsFilterTest extends BaseTestCase
{
    const FILTER = 'basic_tags';

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
            ['<script>alert(1);</script>hello', 'alert(1);hello']
        ];
    }
}