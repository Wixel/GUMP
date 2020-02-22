<?php

namespace Tests;

use GUMP;
use Exception;

/**
 * Class GetErrorsArrayTest
 *
 * @package Tests
 */
class FilterTest extends BaseTestCase
{
    public function testGumpFilterIsSuccessfullyRun()
    {
        $result = $this->gump->filter([
            'test' => 'text'
        ], [
            'test' => 'upper_case'
        ]);

        $this->assertEquals([
            'test' => 'TEXT'
        ], $result);
    }

    public function testPHPNativeFilterIsSuccessfullyRun()
    {
        $result = $this->gump->filter([
            'test' => 'TEXT'
        ], [
            'test' => 'strtolower'
        ]);

        $this->assertEquals([
            'test' => 'text'
        ], $result);
    }

    public function testCustomFilterIsSuccessfullyRun()
    {
        GUMP::add_filter("custom", function($value, $params = NULL) {
            return strtoupper($value);
        });

        $result = $this->gump->filter([
            'test' => 'text'
        ], [
            'test' => 'custom'
        ]);

        $this->assertEquals([
            'test' => 'TEXT'
        ], $result);
    }

    public function testNonexistentFilterThrowsException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Filter method 'custom' does not exist.");

        $this->gump->filter([
            'test' => 'text'
        ], [
            'test' => 'custom'
        ]);
    }

    public function testWhenApplyingFilterToUnknownFieldContinuesWithNextField()
    {
        $result = $this->gump->filter([
            'test' => 'text',
            'other' => 'text'
        ], [
            'non_existent' => 'upper_case',
            'other' => 'upper_case',
        ]);

        $this->assertEquals([
            'test' => 'text',
            'other' => 'TEXT'
        ], $result);
    }

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