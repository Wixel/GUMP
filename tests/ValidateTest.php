<?php

namespace Tests;

use GUMP;
use Exception;

/**
 * Class ValidateTest
 *
 * @package Tests
 */
class ValidateTest extends BaseTestCase
{
    public function testIntegratedValidatorReturnsOneErrorOnOneFailure()
    {
        $result = $this->gump->validate([
            'test' => 'text'
        ], [
            'test' => 'numeric'
        ]);

        $this->assertTrue(count($result) === 1);
    }

    public function testCustomValidatorReturnsOneErrorOnOneFailure()
    {
        GUMP::add_validator("custom2", function($field, $input, $param = NULL) {
            return $input[$field] === 'ok';
        }, 'My custom error');

        $result = $this->gump->validate([
            'test' => 'notOk'
        ], [
            'test' => 'custom2'
        ]);

        $this->assertTrue(count($result) === 1);
    }

    public function testIntegratedValidatorWithIntegratedValidatorReturnsOneErrorOnTwoFailures()
    {
        $result = $this->gump->validate([
            'test' => 'text'
        ], [
            'test' => 'numeric|numeric'
        ]);

        $this->assertTrue(count($result) === 1);
    }

    public function testCustomValidatorWithIntegratedValidatorReturnsOneErrorOnTwoFailures()
    {
        GUMP::add_validator("custom3", function($field, $input, $param = NULL) {
            return $input[$field] === 'ok';
        }, 'My custom error');

        $result = $this->gump->validate([
            'test' => 'notOk'
        ], [
            'test' => 'custom3|numeric'
        ]);

        $this->assertTrue(count($result) === 1);
    }

    public function testIntegratedValidatorWithCustomValidatorReturnsOneErrorOnTwoFailures()
    {
        GUMP::add_validator("custom4", function($field, $input, $param = NULL) {
            return $input[$field] === 'ok';
        }, 'My custom error');

        $result = $this->gump->validate([
            'test' => 'notOk'
        ], [
            'test' => 'numeric|custom4'
        ]);

        $this->assertTrue(count($result) === 1);
    }

    public function testCustomValidatorWithCustomValidatorReturnsOneErrorOnTwoFailures()
    {
        GUMP::add_validator("custom5", function($field, $input, $param = NULL) {
            return $input[$field] === 'ok';
        }, 'My custom error');

        $result = $this->gump->validate([
            'test' => 'notOk'
        ], [
            'test' => 'custom5|custom5'
        ]);

        $this->assertTrue(count($result) === 1);
    }

    public function testValidateThrowsExceptionOnNonexistentValidator()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Validator method 'validate_custom' does not exist.");

        $result = $this->gump->validate([
            'test' => 'notOk'
        ], [
            'test' => 'custom'
        ]);
    }

    public function testValidatePassesParametersToIntegratedValidators()
    {
        $extendedGUMP = new class extends GUMP {
            public function validate_integrated($field, $input, $param = null)
            {
                if ($input[$field] == $param) {
                    return;
                }

                return array(
                    'field' => $field,
                    'value' => $input[$field],
                    'rule' => __FUNCTION__,
                    'param' => $param,
                );
            }
        };

        $result = $extendedGUMP->validate([
            'test' => 'parameterValue'
        ], [
            'test' => 'integrated,parameterValue'
        ]);

        $this->assertTrue($result);
    }

    public function testCustomValidatorsReceiveRightInput()
    {
        $gumpInput = [
            'test' => 'failing'
        ];

        $gumpRuleset = [
            'test' => 'custom,parameterValue'
        ];

        GUMP::add_validator("custom", function($field, $input, $param = NULL) use($gumpInput) {
            $this->assertEquals($field, 'test');
            $this->assertEquals($input, $gumpInput);
            $this->assertEquals($param, 'parameterValue');

            return $input[$field] == 'fail';
        }, 'My custom error');

        $result = $this->gump->validate($gumpInput, $gumpRuleset);
    }

    public function testCustomValidatorsReturnRightErrorStructure()
    {
        GUMP::add_validator("custom", function($field, $input, $param = NULL) {
            return $input[$field] == 'fail';
        }, 'My custom error');

        $result = $this->gump->validate([
            'test' => 'failing'
        ], [
            'test' => 'custom,parameterValue'
        ]);

        $this->assertEquals([[
            'field' => 'test',
            'value' => 'failing',
            'rule' => 'custom',
            'param' => 'parameterValue'
        ]], $result);
    }

    public function testRequired()
    {
        $result = $this->gump->validate([
            'test' => 'failing'
        ], [
            'nonexistent' => 'required'
        ]);

        $this->assertTrue(count($result) ===1);
    }
}