<?php

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class ValidateExtensionTest extends TestCase
{
    private $prophet;

    private $gump;

    public function setUp(): void
    {
        $this->prophet = new Prophet;
        $this->gump = new GUMP;
    }

    protected function tearDown(): void
    {
        $this->prophet->checkPredictions();
    }

    public function testValidate()
    {

    }

//    public function testCustomValidators()
//    {
//        GUMP::add_validator("is_one", function($field, $input, $param = NULL) {
//            return $input[$field] == 1;
//        }, 'test');
//        GUMP::add_validator("is_two", function($field, $input, $param = NULL) {
//            return $input[$field] == 2;
//        }, 'test');
//
//        $result = $this->gump->validate([
//            'test' => 'asd',
//            'test2' => 'hey'
//        ], [
//            'test' => 'min_len,6|numeric|is_two|is_one',
//            'test2' => 'is_two|min_len,6',
////            'test2' => 'min_len,6|is_one',
//        ]);
//
//        $this->assertTrue($this->gump->get_errors_array());
//    }

    public function testInvalidJson()
    {
        $data = array(
            'street' => '"test:true}'
        );

        $validated = GUMP::is_valid($data, array(
            'street' => 'valid_json_string'
        ));

        $this->assertIsArray($validated);
    }
}