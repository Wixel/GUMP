<?php

namespace Tests\Validators;

use Tests\BaseTestCase;
use GUMP;

class CamelCaseValidatorTest extends BaseTestCase
{
    public function testSuccessWhenValidCamelCase()
    {
        $validCamelCase = [
            'camelCase',
            'myVariableName',
            'getElementById',
            'userName',
            'firstName',
            'a',
            'aB',
            'myVar123'
        ];

        foreach ($validCamelCase as $value) {
            $this->assertTrue(GUMP::is_valid([
                'variable' => $value
            ], [
                'variable' => 'camel_case'
            ]), "Failed asserting that '$value' is valid camelCase");
        }
    }

    public function testErrorWhenInvalidCamelCase()
    {
        $invalidCamelCase = [
            'PascalCase',
            'snake_case',
            'kebab-case',
            'UPPER_CASE',
            'camelCase_mixed',
            'camelCase-mixed',
            'camelCase with spaces',
            '123startWithNumber',
            ''
        ];

        foreach ($invalidCamelCase as $value) {
            $this->assertNotSame(true, GUMP::is_valid([
                'variable' => $value
            ], [
                'variable' => 'required|camel_case'
            ]), "Failed asserting that '$value' is invalid camelCase");
        }
    }
}