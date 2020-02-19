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
        $this->assertTrue($this->validate('contains,one two', 'one'));
    }

    public function testFailure()
    {
        $this->assertNotTrue($this->validate('contains,one two', 'three'));
    }

    public function testSuccessWithRegexSeparator()
    {
        $this->assertTrue($this->validate("contains,#'one'##'two'#", 'one'));
    }

    public function testFailureWithRegexSeparator()
    {
        $this->assertNotTrue($this->validate("contains,#'one'##'two'#", 'three'));
    }

    public function testFailureWithRegexSeparatora()
    {
        $this->assertNotTrue($this->validate("contains,#'one'##'two'#", ''));
    }
}