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

    public function testReturnsEmptyStringWhenNoErrorsAndConvertingToString()
    {
        $result = $this->gump->validate([
            'test_number' => '123'
        ], [
            'test_number' => 'numeric'
        ]);

        $this->assertEquals('', $this->gump->get_readable_errors(true));
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
            'test_number' => 'text',
            'test_name' => '123',
            'test_email' => 'notemail.com',
        ], [
            'test_number' => 'numeric',
            'test_name' => 'valid_name',
            'test_email' => 'valid_email'
        ]);

        $this->assertEquals(
            '<span class="gump-error-message">The <span class="gump-field">Test Number</span> field must be a number</span>'
            .'<span class="gump-error-message">The <span class="gump-field">Test Name</span> should be a full name</span>'
            .'<span class="gump-error-message">The <span class="gump-field">Test Email</span> field must be a valid email address</span>',
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

    public function testWhenGumpInstanceIsCastedToStringItReturnsReadableErrorsInStringFormat()
    {
        $result = $this->gump->validate([
            'test_number' => 'text',
            'test_name' => '123',
        ], [
            'test_number' => 'numeric',
            'test_name' => 'valid_name|numeric'
        ]);

        $this->assertEquals(
            '<span class="gump-error-message">The <span class="gump-field">Test Number</span> field must be a number</span>'
            .'<span class="gump-error-message">The <span class="gump-field">Test Name</span> should be a full name</span>',
            (string)$this->gump
        );
    }

    public function testCustomFieldsErrorMessages()
    {
        $this->gump->set_fields_error_messages([
            'test_number' => [
                'between_len' => '{field} length MUST be between {param[0]} and {param[1]} !!!'
            ]
        ]);

        $this->gump->validate([
            'test_number' => '123'
        ], [
            'test_number' => 'between_len,1;2'
        ]);

        $this->assertEquals([
            '<span class="gump-field">Test Number</span> length MUST be between 1 and 2 !!!'
        ], $this->gump->get_readable_errors());
    }
}