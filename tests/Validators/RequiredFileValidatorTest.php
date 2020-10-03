<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class RequiredFileValidatorTest
 *
 * @package Tests
 */
class RequiredFileValidatorTest extends BaseTestCase
{
    const RULE = 'required_file';

    public function testItFailsWhenThereIsNoInputFile()
    {
        $result = $this->gump->validate([], [
            'test' => self::RULE
        ]);

        $this->assertNotTrue($result);
    }

    public function testWhenFileIsSuccessfullyUploadedItSuccesses()
    {
        $input = [
            'name' => 'screenshot.png',
            'type' => 'image/png',
            'tmp_name' => '/tmp/phphjatI9',
            'error' => 0,
            'size' => 22068
        ];

        $this->assertTrue($this->validate(self::RULE, $input));
    }

    public function testWhenFileIsNotSuccessfullyUploadedItFails()
    {
        $input = [
            'name' => 'document.pdf',
            'type' => 'application/pdf',
            'tmp_name' => '/tmp/phphjatI9',
            'error' => 4,
            'size' => 22068
        ];

        $this->assertNotTrue($this->validate(self::RULE, $input));
    }
}