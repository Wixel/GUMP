<?php

namespace Tests;

/**
 * Class ValidateTest
 *
 * @package Tests
 */
class ValidDataTest extends BaseTestCase
{
    public function testOnFailureReturnsEmptyArray()
    {
        $this->gump->validation_rules(array(
            'field0' => 'required',
        ));

        $result = $this->gump->validData([
            'field1' => 'somedata'
        ]);

        $this->assertEquals([], $result);
    }

    public function testOnSuccessReturnsOnlyValidDataWithFiltersApplied()
    {
        $this->gump->validation_rules(array(
            'field0' => 'required|numeric',
            'field1' => 'required',
        ));

        $this->gump->filter_rules(array(
            'field0' => 'trim'
        ));

        $result = $this->gump->validData([
            'field0' => ' 123 ',
            'field1' => 'somedata',
            'field2' => 'test'
        ]);

        $this->assertEquals([
            'field0' => '123',
            'field1' => 'somedata'
        ], $result);
    }
}
