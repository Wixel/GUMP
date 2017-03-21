<?php
// Testing against http://isemail.info/_system/is_email/test/?all


class MaxLenTest extends PHPUnit_Framework_TestCase
{

    private $gump;

    public function setUp()
    {
        $this->gump = new GUMP;
        $this->gump->validation_rules(["input_value" => "max_len,5"]);
    }

    /**
     * @dataProvider test_MaxLen_ValidInputProvider
     */

    public function test_MaxLen_ValidInput($value)
    {
        $input = ["input_value" => $value];
        $validated_data = $this->gump->run($input);
        $this->assertEquals($input, $validated_data);
    }

    public function test_MaxLen_ValidInputProvider()
    {
        return [
            [''],
            [""],
            [true],
            ["true"],
            ['true'],
            [false],
            ["false"],
            ['false'],
            ["null"],
            ['null'],
            [null],
            [0],
            ["0"],
            ['0'],
            [1],
            ["1"],
            ['1'],
            ["\n"],
            ["\r"],
            ["\r\n"],
            [100],
            ["4000"],
            ["\r\n\n\n\n"]
        ];
    }

    /**
     * @dataProvider test_MaxLen_InvalidInputProvider
     */

    public function test_MaxLen_InalidInput($value)
    {
        $input = ["input_value" => $value];
        $validated_data = $this->gump->run($input);
        $this->assertFalse($validated_data);
    }

    public function test_MaxLen_InvalidInputProvider()
    {
        return [
            ['test@iana.123'],
            ['123456'],
            ['123456']
        ];
    }
}
