<?php

namespace Tests;

use GUMP;
use Exception;

/**
 * Class GetErrorsArrayTest
 *
 * @package Tests
 */
class GetReadableErrorsTest extends BaseTestCase
{
    public function testReturnsEmptyArrayWhenNoErrors()
    {
        $result = $this->gump->validate([
            'test_number' => '123'
        ], [
            'test_number' => 'numeric'
        ]);

        $this->assertEquals([], $this->gump->get_readable_errors());
    }

    public function testReturnsNullWhenNoErrorsAndConvertingToString()
    {
        $result = $this->gump->validate([
            'test_number' => '123'
        ], [
            'test_number' => 'numeric'
        ]);

        $this->assertNull($this->gump->get_readable_errors(true));
    }

    public function testReturnsArray()
    {
        $result = $this->gump->validate([
            'test_number' => 'text'
        ], [
            'test_number' => 'numeric'
        ]);

        $this->assertEquals([
            "The <span class=\"gump-field\">Test Number</span> field must be a number"
        ], $this->gump->get_readable_errors());
    }

    public function testReturnsString()
    {
        $result = $this->gump->validate([
            'test_number' => 'text'
        ], [
            'test_number' => 'numeric'
        ]);

        $this->assertEquals(
            '<span class="gump-error-message">The <span class="gump-field">Test Number</span> field must be a number</span>',
            $this->gump->get_readable_errors(true)
        );
    }

    public function testPrintsCustomFieldLabelsOnErrors()
    {
        GUMP::set_field_name('testnumber', 'Test Num.');

        $result = $this->gump->validate([
            'testnumber' => 'hey'
        ], [
            'testnumber' => 'numeric'
        ]);

        $this->assertEquals([
            'The <span class="gump-field">Test Num.</span> field must be a number'
        ], $this->gump->get_readable_errors());
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

        $this->gump->get_readable_errors();
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
            'The <span class="gump-field">Test Num.</span> field does not equal The Other Test Field field'
        ], $this->gump->get_readable_errors());
    }
}