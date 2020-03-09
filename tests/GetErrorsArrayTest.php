<?php

namespace Tests;

use GUMP;
use Exception;

/**
 * Class GetErrorsArrayTest
 *
 * @package Tests
 */
class GetErrorsArrayTest extends BaseTestCase
{
    public function testReturnsEmptyArrayWhenNoErrors()
    {
        $this->gump->validate([
            'test_number' => '111'
        ], [
            'test_number' => 'numeric'
        ]);

        $this->assertEquals([], $this->gump->get_errors_array());
    }

    public function testReturnsErrorsWithFieldAsKey()
    {
        $result = $this->gump->validate([
            'test_number' => 'aaa'
        ], [
            'test_number' => 'numeric'
        ]);

        $this->assertArrayHasKey('test_number', $this->gump->get_errors_array());
    }

    public function testReturnsErrorsWithErrorMessageOfCustomValidator()
    {
        GUMP::add_validator("custom", function($field, $input, $param = null) {
            return $input[$field] === 'ok';
        }, 'Custom error message');

        $this->gump->validate([
            'test_number' => 'notOk'
        ], [
            'test_number' => 'custom'
        ]);

        $this->assertEquals([
            'test_number' => 'Custom error message'
        ], $this->gump->get_errors_array());
    }

    public function testErrorMessageReplacesReferencedFieldNameToo()
    {
        GUMP::set_field_name('test_number', 'Test Num.');
        GUMP::set_field_name('other_field', 'The Other Test Field');

        $result = $this->gump->validate([
            'test_number' => '111',
            'other_field' => '1112'
        ], [
            'test_number' => 'equalsfield,other_field'
        ]);

        $this->assertEquals([
            'test_number' => 'The Test Num. field does not equal The Other Test Field field'
        ], $this->gump->get_errors_array());
    }

    public function testErrorMessagePropagatesParamsArrayKeysToErrorMessages()
    {
        GUMP::add_validator("num_index", function($field, $input, $param = null) {
            return $input[$field] === 'ok';
        }, 'Parameter one: {param[0]} and parameter two: {param[1]}');

        GUMP::add_validator("text_index", function($field, $input, $param = null) {
            return $input[$field] === 'ok';
        }, 'Parameter one: {param[ten]} and parameter two: {param[twenty]}');

        $result = $this->gump->validate([
            'first_test' => 'notOk',
            'second_test' => 'notOk',
        ], [
            'first_test' => 'num_index,1;2',
            'second_test' => ['text_index' => ['ten' => 10, 'twenty' => 20]],
        ]);

        $this->assertEquals([
            'first_test' => 'Parameter one: 1 and parameter two: 2',
            'second_test' => 'Parameter one: 10 and parameter two: 20',
        ], $this->gump->get_errors_array());
    }

    public function testErrorMessageSplitsArrayParameterWithCommas()
    {
        GUMP::add_validator("custom", function($field, $input, $param = null) {
            return $input[$field] === 'ok';
        }, 'Separated by comma: {param}');

        $result = $this->gump->validate([
            'test_number' => 'notOk'
        ], [
            'test_number' => 'custom,1;2'
        ]);

        $this->assertEquals([
            'test_number' => 'Separated by comma: 1, 2'
        ], $this->gump->get_errors_array());
    }

    public function testItThrowsExceptionWhenCustomValidatorFailsWithoutAnErrorMessageSet()
    {
        GUMP::add_validator("custom", function($field, $input, $param = null) {
            return $input[$field] === 'ok';
        });

        $this->gump->validate([
            'test_number' => 'notOk'
        ], [
            'test_number' => 'custom'
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Rule "custom" does not have an error message');

        $this->gump->get_errors_array();
    }

    public function testCustomFieldsErrorMessages()
    {
        $this->gump->validate([
            'test_number' => '123'
        ], [
            'test_number' => 'between_len,1;2'
        ], [
            'test_number' => [
                'between_len' => '{field} length MUST be between {param[0]} and {param[1]} !!!'
            ]
        ]);

        $this->assertEquals([
            'test_number' => 'Test Number length MUST be between 1 and 2 !!!'
        ], $this->gump->get_errors_array());
    }
}