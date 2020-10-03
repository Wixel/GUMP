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
    public function testConfigParameters()
    {
        $current_rules_delimiter =  GUMP::$rules_delimiter;
        $current_rules_parameters_delimiter =  GUMP::$rules_parameters_delimiter;
        $current_rules_parameters_arrays_delimiter =  GUMP::$rules_parameters_arrays_delimiter;

        GUMP::$rules_delimiter = ';';
        GUMP::$rules_parameters_delimiter = ':';
        GUMP::$rules_parameters_arrays_delimiter = ',';

        $result = $this->gump->validate([
            'some_field' => 'no'
        ], [
            'some_field' => 'alpha;contains_list:yes,no',
        ]);

        $this->assertTrue($result);

        // reset
        GUMP::$rules_delimiter = $current_rules_delimiter;
        GUMP::$rules_parameters_delimiter = $current_rules_parameters_delimiter;
        GUMP::$rules_parameters_arrays_delimiter = $current_rules_parameters_arrays_delimiter;
    }

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
        GUMP::add_validator("custom2", function($field, $input, array $params = []) {
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
        GUMP::add_validator("custom3", function($field, $input, array $params = []) {
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
        GUMP::add_validator("custom4", function($field, $input, array $params = []) {
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
        GUMP::add_validator("custom5", function($field, $input, array $params = []) {
            return $input[$field] === 'ok';
        }, 'My custom error');

        $result = $this->gump->validate([
            'test' => 'notOk'
        ], [
            'test' => 'custom5|custom5'
        ]);

        $this->assertTrue(count($result) === 1);
    }

    public function testIntegratedValidatorWithCustomValidatorBothFailingOnDifferentFields()
    {
        GUMP::add_validator('custom', function($field, $input, array $params = []) {
            return $input[$field] === 'ok';
        }, 'My custom error');

        $result = $this->gump->validate([
            'integrated' => 'text',
            'custom' => 'notOk'
        ], [
            'integrated' => 'numeric',
            'custom' => 'custom'
        ]);

        $this->assertTrue(count($result) === 2);
    }

    public function testIntegratedValidatorWithCustomValidatorFailingIntegratedOnDifferentFields()
    {
        GUMP::add_validator('custom', function($field, $input, array $params = []) {
            return $input[$field] === 'ok';
        }, 'My custom error');

        $result = $this->gump->validate([
            'integrated' => 'text',
            'custom' => 'ok'
        ], [
            'integrated' => 'numeric',
            'custom' => 'custom'
        ]);

        $this->assertTrue(count($result) === 1);
    }

    public function testIntegratedValidatorWithCustomValidatorFailingCustomOnDifferentFields()
    {
        GUMP::add_validator('custom', function($field, $input, array $params = []) {
            return $input[$field] === 'ok';
        }, 'My custom error');

        $result = $this->gump->validate([
            'integrated' => '123',
            'custom' => 'notOk'
        ], [
            'integrated' => 'numeric',
            'custom' => 'custom'
        ]);

        $this->assertTrue(count($result) === 1);
    }

    public function testValidateThrowsExceptionOnNonexistentValidator()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("'custom' validator does not exist.");

        $result = $this->gump->validate([
            'test' => 'notOk'
        ], [
            'test' => 'custom'
        ]);
    }

    public function testValidatePassesParametersToIntegratedValidators()
    {
        $extendedGUMP = new class extends GUMP {
            protected function validate_integrated($field, $input, array $params = [])
            {
                return $input[$field] === $params[0];
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

        GUMP::add_validator('custom', function($field, $input, array $params = []) use($gumpInput) {
            $this->assertEquals($field, 'test');
            $this->assertEquals($input, $gumpInput);
            $this->assertEquals($params, ['parameterValue']);

            return $input[$field] == 'fail';
        }, 'My custom error');

        $result = $this->gump->validate($gumpInput, $gumpRuleset);
    }

    public function testCustomValidatorsReturnRightErrorStructure()
    {
        GUMP::add_validator('custom', function($field, $input, array $params = []) {
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
            'params' => ['parameterValue']
        ]], $result);
    }

    public function testIntegratedValidatorsReturnRightErrorStructure()
    {
        $result = $this->gump->validate([
            'test' => '123'
        ], [
            'test' => 'date,Y-m-d'
        ]);

        $this->assertEquals([[
            'field' => 'test',
            'value' => '123',
            'rule' => 'date',
            'params' => ['Y-m-d']
        ]], $result);
    }

    public function testRequiredValidatorReturnsRightErrorStructure()
    {
        $result = $this->gump->validate([
            'test' => ''
        ], [
            'test' => 'required'
        ]);

        $this->assertEquals($result, [[
            'field' => 'test',
            'value' => '',
            'rule' => 'required',
            'params' => []
        ]]);
    }

    public function testRequiredAndRequiredFile()
    {
        $result = $this->gump->validate([
            'field_without_validation_rules' => '123'
        ], [
            'some_field' => 'required',
            'file_field' => 'required_file',
        ]);

        $this->assertEquals($result, [[
            'field' => 'some_field',
            'value' => null,
            'rule' => 'required',
            'params' => []
        ], [
            'field' => 'file_field',
            'value' => null,
            'rule' => 'required_file',
            'params' => []
        ]]);
    }

    public function testOnlyFirstErrorOfTheSameFieldIsReturnedAsError()
    {
        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnTrue();

        $result = $this->gump->validate([
            'some_field' => '123'
        ], [
            'some_field' => 'alpha|max_len,2',
        ]);

        $this->assertEquals([[
            'field' => 'some_field',
            'value' => '123',
            'rule' => 'alpha',
            'params' => []
        ]], $result);
    }

    public function testRulesArrayFormatWithOneParameter()
    {
        $result = $this->gump->validate([
            'some_field' => 'test',
            'some_other_field' => '123'
        ], [
            'some_field' => ['required', 'alpha', 'max_len' => 2],
            'some_other_field' => 'alpha',
        ]);

        $this->assertEquals([[
            'field' => 'some_field',
            'value' => 'test',
            'rule' => 'max_len',
            'params' => [2]
        ], [
            'field' => 'some_other_field',
            'value' => '123',
            'rule' => 'alpha',
            'params' => []
        ]], $result);
    }

    public function testRulesArrayFormatIgnoresNonRequiredFields()
    {
        $result = $this->gump->validate([
            'some_other_field' => null
        ], [
            'some_field' => ['boolean', 'min_len' => 2],
            'some_other_field' => ['integer', 'min_len' => 2],
        ]);

        $this->assertTrue($result);
    }

    public function testRulesArrayFormatChecksRequiredFields()
    {
        $result = $this->gump->validate([], [
            'some_field' => ['required', 'boolean', 'min_len' => 2],
            'some_other_field' => ['required', 'integer', 'min_len' => 2],
        ]);

        $this->assertEquals($result, [[
            'field' => 'some_field',
            'value' => null,
            'rule' => 'required',
            'params' => []
        ], [
            'field' => 'some_other_field',
            'value' => null,
            'rule' => 'required',
            'params' => []
        ]]);
    }

    public function testRulesArrayFormatWithSimpleArrayParameters()
    {
        $result = $this->gump->validate([
            'some_field' => 'tests'
        ], [
            'some_field' => ['required', 'alpha', 'between_len' => [2, 4]],
        ]);

        $this->assertEquals([[
            'field' => 'some_field',
            'value' => 'tests',
            'rule' => 'between_len',
            'params' => [2, 4]
        ]], $result);
    }

    public function testRulesWithSemicolonSeparatorMapsToArrayInsideValidator()
    {
        $result = $this->gump->validate([
            'some_field' => 'tests'
        ], [
            'some_field' => 'alpha|between_len,2;4',
        ]);

        $this->assertEquals([[
            'field' => 'some_field',
            'value' => 'tests',
            'rule' => 'between_len',
            'params' => [2, 4] // ;)
        ]], $result);
    }

    public function testRulesArrayFormatWithMultidimensionalArrayParameters()
    {
        GUMP::add_validator('custom', function($field, $input, array $param) {
            return $param['min'] === 2 && $param['max'] === 5;
        }, 'My custom error');

        $result = $this->gump->validate([
            'some_field' => 'tests'
        ], [
            'some_field' => ['required', 'alpha', 'custom' => ['min' => 2, 'max' => 5]],
        ]);

        $this->assertTrue($result);
    }
}