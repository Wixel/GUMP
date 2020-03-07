<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class ContainsValidatorTest
 *
 * @package Tests
 */
class ContainsValidatorTest extends BaseTestCase
{
    public function testSuccess()
    {
        $this->assertTrue($this->validate("contains,'one' 'two' 'space separated'", 'space separated'));
    }

    public function testSuccessWithSemicolonFormat()
    {
        $this->assertTrue($this->validate("contains,one;two;as long as it does not contain a semicolon", 'as long as it does not contain a semicolon'));
    }

    public function testFailure()
    {
        $this->assertNotTrue($this->validate('contains,one two', 'three'));
    }

    public function testFailureWithSemicolonFormat()
    {
        $this->assertNotTrue($this->validate("contains,one;two;as long as it does not contain a semicolon", 'as long as it does not contain a'));
    }

    public function testSuccessWithRegexSeparator()
    {
        $this->assertTrue($this->validate("contains,'one' 'two' 'half three'", 'half three'));
    }

    public function testFailureWithRegexSeparator()
    {
        $this->assertNotTrue($this->validate("contains,'one' 'two'", 'three'));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
        $this->assertTrue($this->validate("contains,'one' 'two'", ''));
    }
}