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
        $result = $this->gump->validate([
            'test_number' => '111'
        ], [
            'test_number' => 'numeric'
        ]);

        $this->assertEquals([], $this->gump->get_errors_array());
    }

    public function testReturnsNullWhenNoErrorsAndConvertingToStringIsSet()
    {
        $result = $this->gump->validate([
            'test_number' => '111'
        ], [
            'test_number' => 'numeric'
        ]);

        $this->assertNull($this->gump->get_errors_array(true));
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

    public function testPrintsCustomFieldLabelsOnErrors()
    {
        GUMP::set_field_name('test', 'Test Num.');

        $result = $this->gump->validate([
            'test' => 'hey'
        ], [
            'test' => 'numeric'
        ]);

        $this->assertEquals([
            'test' => 'The Test Num. field must be a number'
        ], $this->gump->get_errors_array());
    }

    public function testThrowsExceptionOnValidatorWithoutErrorMessage()
    {
        GUMP::add_validator("custom", function($field, $input, $param = NULL) {
            return $input[$field] === 'ok';
        });

        $result = $this->gump->validate([
            'testnumber' => 'hey'
        ], [
            'testnumber' => 'custom'
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Rule "custom" does not have an error message');

        $this->gump->get_errors_array();
    }


    public function testEqualsFieldValidator()
    {
        GUMP::set_field_name('test_number', 'Test Num.');
        GUMP::set_field_name('test', 'The Other Test Field');

        $result = $this->gump->validate([
            'test_number' => '111',
            'test' => '1112'
        ], [
            'test_number' => 'equalsfield,test'
        ]);

        $this->assertEquals([
            'test_number' => 'The Test Num. field does not equal The Other Test Field field'
        ], $this->gump->get_errors_array());
    }
}