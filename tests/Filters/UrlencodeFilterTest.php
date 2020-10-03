<?php

namespace Tests\Filters;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class UrlencodeFilterTest
 *
 * @package Tests
 */
class UrlencodeFilterTest extends BaseTestCase
{
    const FILTER = 'urlencode';

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
            ['https://www.domainÅÅ.com', 'https%3A%2F%2Fwww.domain%C3%85%C3%85.com'],
        ];
    }
}