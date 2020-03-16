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

    public function testNestedArays()
    {
        $this->gump->validation_rules([
            'some_field' => ['required', 'alpha'],
        ]);

//        // set field-rule specific error messages
//        $this->gump->set_fields_error_messages([
//            'some_field.name' => ['required' => 'Fill the Username field please, its required.'],
//        ]);

        $result = $this->gump->run([
            'some_field' => ['a', 'b']
        ]);

        $this->assertTrue($result);

        $this->assertEquals([[
            'field' => 'mismatch',
            'value' => 'somedata',
            'rule' => 'mismatch',
            'params' => []
        ]], $this->gump->errors());
    }
}