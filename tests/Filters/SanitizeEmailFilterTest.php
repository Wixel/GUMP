<?php

namespace Tests\Filters;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class SanitizeEmailFilterTest
 *
 * @package Tests
 */
class SanitizeEmailFilterTest extends BaseTestCase
{
    const FILTER = 'sanitize_email';

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
            ['test"º@email.com', 'test@email.com'],
        ];
    }
}