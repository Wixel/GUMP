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
    public function testIntegratedFilterIsSuccessfullyRun()
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

        $result = $this->gump->filter([
            'test' => 'text'
        ], [
            'test' => 'custom'
        ]);
    }

    public function testApplyFilterToUnknownFieldContinuesWithNextField()
    {
        $result = $this->gump->filter([
            'test' => 'text',
            'other' => 'text'
        ], [
            'testa' => 'upper_case',
            'other' => 'upper_case',
        ]);

        $this->assertEquals([
            'test' => 'text',
            'other' => 'TEXT'
        ], $result);
    }
}