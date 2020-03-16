<?php

namespace Tests;

use GUMP;
use Exception;

/**
 * Class RunTest
 *
 * @package Tests
 */
class RunTest extends BaseTestCase
{
    public function testOnFailureReturnsFalse()
    {
        $this->gump->validation_rules(array(
            'test'    => 'numeric'
        ));

        $this->gump->filter_rules(array(
            'test' => 'trim'
        ));

        $result = $this->gump->run([
            'test' => ' asd'
        ]);

        $this->assertFalse($result);
    }

    public function testOnSuccessReturnsArrayWithFiltersApplied()
    {
        $this->gump->validation_rules(array(
            'test'    => 'numeric'
        ));

        $this->gump->filter_rules(array(
            'test' => 'trim'
        ));

        $result = $this->gump->run([
            'test' => ' 123 '
        ]);

        $this->assertEquals([
            'test' => '123'
        ], $result);
    }

    public function testOnSuccessReturnsArrayWithFiltersAppliedWithCheckFieldsSet()
    {
        $this->gump->validation_rules(array(
            'test'    => 'numeric'
        ));

        $this->gump->filter_rules(array(
            'test' => 'trim'
        ));

        $result = $this->gump->run([
            'mismatch' => 'somedata',
            'test' => ' 123 '
        ], true);

        $this->assertEquals([
            'test' => '123',
            'mismatch' => 'somedata'
        ], $result);

        $this->assertEquals([[
            'field' => 'mismatch',
            'value' => 'somedata',
            'rule' => 'mismatch',
            'params' => []
        ]], $this->gump->errors());
    }

    public function testNestedArrays()
    {
        $this->gump->validation_rules([
            'field0' => ['required'],
            'field1.name' => ['required', 'alpha'],
            'field2.*.name' => ['required', 'alpha_numeric'],
        ]);

        GUMP::set_field_name('field1.name', 'Object Name');

        $this->gump->set_fields_error_messages([
            'field1.name' => ['alpha' => '{field} must contain alpha characters']
        ]);

        $result = $this->gump->run([
            'field0' => ['asd', ''],
            'field1' => [
                'name' => 'test123'
            ],
            'field2' => [
                [
                    'name' => '123'
                ],
                [
                    'name' => ''
                ],
            ]
        ]);

        $this->assertEquals([
            [
                'field' => 'field0',
                'value' => ['asd', ''],
                'rule' => 'required',
                'params' => []
            ],
            [
                'field' => 'field1.name',
                'value' => 'test123',
                'rule' => 'alpha',
                'params' => []
            ],
            [
                'field' => 'field2.*.name',
                'value' => [ '123', '' ],
                'rule' => 'required',
                'params' => []
            ]
        ], $this->gump->errors());

        $this->assertEquals([
            'field0' => 'The Field0 field is required',
            'field1.name' => 'Object Name must contain alpha characters',
            'field2.*.name' => 'The Field2.*.name field is required'
        ], $this->gump->get_errors_array());
    }
}