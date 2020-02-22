<?php

namespace Tests;

use GUMP;
use Exception;

/**
 * Class StaticFilterInputTest
 *
 * @package Tests
 */
class StaticFilterInputTest extends BaseTestCase
{
    public function testStaticFilterInputCall()
    {
        $result = GUMP::filter_input([
            'other' => 'text'
        ], [
            'other' => 'upper_case',
        ]);

        $this->assertEquals([
            'other' => 'TEXT'
        ], $result);
    }
}