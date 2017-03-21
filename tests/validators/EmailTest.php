<?php
// Testing against http://isemail.info/_system/is_email/test/?all


class EmailTest extends PHPUnit_Framework_TestCase
{

    private $gump;

    public function setUp()
    {
        $this->gump = new GUMP;
        $this->gump->validation_rules(['input_value' => 'valid_email']);
    }

    /**
     * @dataProvider test_Email_ValidInputProvider
     */

    public function test_Email_ValidInput($value)
    {
        $input = ['input_value' => $value];
        $validated_data = $this->gump->run($input);
        $this->assertEquals($input, $validated_data);
    }

    public function test_Email_ValidInputProvider()
    {
        return [
            [null],
            [false],
            [0],
            ["0"],
            ['0']
            ['test@iana.org'],
            ['test@nominet.org.uk'],
            ['test@about.museum'],
            ['a@iana.org'],
            ['!#$%&`*+/=?^`{|}~@iana.org'],
            ['123@iana.org'],
            ['test@123.com'],
            ['test@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.com'],
            ['test@mason-dixon.com'],
            ['test@c--n.com'],
            ['test@iana.co-uk'],
            ['xn--test@iana.org'],
            ['test@test.com'],
            ['test@nic.no']
        ];
    }

    /**
     * @dataProvider test_Email_InvalidInputProvider
     */

    public function test_Email_InalidInput($value)
    {
        $input = ['input_value' => $value];
        $validated_data = $this->gump->run($input);
        $this->assertFalse($validated_data);
    }

    public function test_Email_InvalidInputProvider()
    {
        return [
            [true],
            ["true"],
            ["false"],
            ["null"],
            [1],
            ["1"],
            ["\n"],
            [100],
            ["4000"],
            ['test@iana.123'],
            ['test@255.255.255.255'],
            ['test@org'],
        ];
    }
}
