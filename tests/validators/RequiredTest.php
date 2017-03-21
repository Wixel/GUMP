<?php

class RequiredTest extends PHPUnit_Framework_TestCase
{

    private $gump;

    public function setUp()
    {
        $this->gump = new GUMP;
        $this->gump->validation_rules(['input_value' => 'required']);
    }

    /**
     * @dataProvider test_Required_ValidInputProvider
     */

    public function test_Required_ValidInput($value)
    {
        $input = ['input_value' => $value];
        $validated_data = $this->gump->run($input);
        $this->assertEquals($input, $validated_data);
    }

    public function test_Required_ValidInputProvider()
    {
        return [
            ['some data'],
            [true],
            ["true"],
            [false],
            ["false"],
            ["null"],
            [0],
            ["0"],
            [1],
            ["1"],
            ["\n"]
        ];
    }

    /**
     * @dataProvider test_Required_InvalidInputProvider
     */

    public function test_Required_InalidInput($value)
    {
        $input = ['input_value' => $value];
        $validated_data = $this->gump->run($input);
        $this->assertFalse($validated_data);
    }

    public function test_Required_InvalidInputProvider()
    {
        return [
            [''],
            [null]
        ];
    }
}
