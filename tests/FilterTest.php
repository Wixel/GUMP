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
    public function testGumpFilterIsSuccessfullyApplied()
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

    public function testMoreThanOneFiltersAreSuccessfullyApplied()
    {
        GUMP::add_filter("custom", function($value, array $params = []) {
            return strtolower($value);
        });

        $result = $this->gump->filter([
            'test' => ' text '
        ], [
            'test' => 'trim|upper_case|custom'
        ]);

        $this->assertEquals([
            'test' => 'text'
        ], $result);
    }

    public function testPHPNativeFilterIsSuccessfullyApplied()
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

    public function testCustomFilterIsSuccessfullyApplied()
    {
        GUMP::add_filter("custom", function($value, array $params = []) {
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
        $this->expectExceptionMessage("'custom' filter does not exist.");

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

    public function testRulesArrayFormatWithSimpleArrayParameters()
    {
        GUMP::add_filter("custom", function($value, array $param) {
            return call_user_func($param[0], $value);
        });

        $result = $this->gump->filter([
            'field_one' => 'tests',
            'field_two' => ' CUSTOM ',
        ], [
            'field_one' => 'upper_case',
            'field_two' => ['trim', 'custom' => 'strtolower'],
        ]);

        $this->assertEquals([
            'field_one' => 'TESTS',
            'field_two' => 'custom',
        ], $result);
    }

    public function testItForwardsParametersToNativePHPFunctions()
    {
        $result = $this->gump->filter([
            'field' => 'my_password',
        ], [
            'field' => ['password_hash' => [ PASSWORD_BCRYPT ] ],
        ]);

        $this->assertTrue(password_verify('my_password', $result['field']));
    }

//    public function testNestedArrays()
//    {
//        $data = [
//            'field0' => [' asd ', ''],
//            'field1' => [
//                'name' => ' test123 '
//            ],
//            'field2' => [
//                [
//                    'name' => ' 123 '
//                ],
//                [
//                    'name' => ' test '
//                ],
//            ]
//        ];
//
//        $result = $this->gump->filter($data, [
//            'field0' => 'trim',
//            'field1.name' => 'trim',
//            'field2.*.name' => 'trim',
//        ]);
//
//        $this->assertEquals([
//            'field0' => ['asd', ''],
//            'field1' => [
//                'name' => 'test123'
//            ],
//            'field2' => [
//                [
//                    'name' => '123'
//                ],
//                [
//                    'name' => 'test'
//                ],
//            ]
//        ], $result);
//    }
}