<?php

namespace Tests\Filters;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class NoiseWordsFilterTest
 *
 * @package Tests
 */
class NoiseWordsFilterTest extends BaseTestCase
{
    const FILTER = 'noise_words';

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
            ['dont know anything about that', 'dont know anything'],
            ['no noise words', 'no noise words'],
        ];
    }
}