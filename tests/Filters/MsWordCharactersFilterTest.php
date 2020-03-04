<?php

namespace Tests\Filters;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class MsWordCharactersFilterTest
 *
 * @package Tests
 */
class MsWordCharactersFilterTest extends BaseTestCase
{
    const FILTER = 'ms_word_characters';

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
            ['“My quote”', '"My quote"'],
            ['‘My quote’', "'My quote'"],
            ['– and then', '- and then'],
            ['And at the end…', 'And at the end...'],
        ];
    }
}