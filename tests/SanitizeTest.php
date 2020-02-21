<?php

namespace Tests;

use GUMP;
use Exception;

/**
 * Class SanitizeTest
 *
 * @package Tests
 */
class SanitizeTest extends BaseTestCase
{
    public function testWhenWhitelistedFieldDoesntExistItContinuesSanitizingNextFields()
    {
        $result = $this->gump->sanitize([
            'whitelisted_field' => "text\r",
        ], ['non_existent', 'whitelisted_field']);

        $this->assertEquals([
            'whitelisted_field' => 'text',
        ], $result);
    }

    public function testArrayValuesAreAlsoSanitizedIfInputIsArray()
    {
        $result = $this->gump->sanitize([
            'whitelisted_field' => ['key' => "text\r"],
        ]);

        $this->assertEquals([
            'whitelisted_field' => ['key' => "text"],
        ], $result);
    }

    public function testWhenWhitelistedFieldsArePresentThenOnlyTheyAreReturnedAndSanitized()
    {
        $result = $this->gump->sanitize([
            'whitelisted_field' => "text\r",
            'test2' => "Žluťoučký kůň",
        ], ['whitelisted_field']);

        $this->assertEquals([
            'whitelisted_field' => 'text',
        ], $result);
    }
}