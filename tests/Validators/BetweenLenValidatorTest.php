<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;
use Mockery as m;

/**
 * Class BetweenLenValidatorTest
 *
 * @package Tests
 */
class BetweenLenValidatorTest extends BaseTestCase
{
    public function testSuccessWhenExactlyMinimum()
    {
        $this->assertTrue($this->validate('between_len,3;10', 'abc'));
    }

    public function testSuccessWhenExactlyMaximum()
    {
        $this->assertTrue($this->validate('between_len,3;10', '1234567890'));
    }

    public function testSuccessWhenInMiddleOfRange()
    {
        $this->assertTrue($this->validate('between_len,3;10', 'hello'));
    }

    public function testSuccessWithUnicodeCharacters()
    {
        $this->assertTrue($this->validate('between_len,3;10', 'ñándú'));
    }

    public function testSuccessWithEmojis()
    {
        $this->assertTrue($this->validate('between_len,1;10', '🌟⭐✨'));
    }

    public function testErrorWhenTooShort()
    {
        $this->assertNotTrue($this->validate('between_len,5;10', 'hi'));
    }

    public function testErrorWhenTooLong()
    {
        $this->assertNotTrue($this->validate('between_len,3;5', 'toolongstring'));
    }

    public function testErrorWhenEmpty()
    {
        $this->assertNotTrue($this->validate('between_len,1;10', ''));
    }

    public function testErrorWhenExactlyBelowMinimum()
    {
        $this->assertNotTrue($this->validate('between_len,5;10', '1234'));
    }

    public function testErrorWhenExactlyAboveMaximum()
    {
        $this->assertNotTrue($this->validate('between_len,3;5', '123456'));
    }

    public function testSuccessWithWhitespace()
    {
        $this->assertTrue($this->validate('between_len,3;10', ' test '));
    }

    public function testSuccessWithSpecialCharacters()
    {
        $this->assertTrue($this->validate('between_len,3;15', '!@#$%^&*()'));
    }

    public function testSuccessWithNumbers()
    {
        $this->assertTrue($this->validate('between_len,5;10', '12345'));
    }

    public function testSuccessWithMixedContent()
    {
        $this->assertTrue($this->validate('between_len,5;20', 'Test123!@#'));
    }

    public function testErrorWithOnlySpaces()
    {
        $this->assertNotTrue($this->validate('between_len,5;10', '   '));
    }

    public function testSuccessWithNewlines()
    {
        $this->assertTrue($this->validate('between_len,5;20', "Line1\nLine2"));
    }

    public function testSuccessWithTabs()
    {
        $this->assertTrue($this->validate('between_len,3;10', "A\tB\tC"));
    }

    public function testBoundaryConditionMinEqualsMax()
    {
        $this->assertTrue($this->validate('between_len,5;5', 'exact'));
    }

    public function testBoundaryConditionZeroMin()
    {
        $this->assertTrue($this->validate('between_len,0;5', 'test'));
    }

    public function testBoundaryConditionZeroMinEmptyString()
    {
        $this->assertTrue($this->validate('between_len,0;5', ''));
    }

    public function testMultibyteStringLength()
    {
        // Test with Chinese characters (each char is multiple bytes but counts as 1 character)
        $this->assertTrue($this->validate('between_len,2;5', '你好'));
    }

    public function testRTLCharacters()
    {
        // Test with Arabic text
        $this->assertTrue($this->validate('between_len,3;10', 'مرحبا'));
    }

    /**
     * Helper method to validate input
     */
    private function validate($rule, $input)
    {
        return $this->gump->validate(['test' => $input], ['test' => $rule]);
    }
}