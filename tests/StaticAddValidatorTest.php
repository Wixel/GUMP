<?php

namespace Tests;

use GUMP;
use Exception;

/**
 * Class StaticAddValidatorTest
 *
 * @package Tests
 */
class StaticAddValidatorTest extends BaseTestCase
{
    public function testItThrowsExceptionWhenValidatorWithSameNameIsAdded()
    {
        GUMP::add_validator("custom", function($field, $input, $param = NULL) {
            return $input[$field] === 'ok';
        });

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Validator rule 'custom' already exists.");

        GUMP::add_validator("custom", function($field, $input, $param = NULL) {
            return $input[$field] === 'ok';
        });
    }
}