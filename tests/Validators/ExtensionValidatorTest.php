<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

/**
 * Class ExtensionValidatorTest
 *
 * @package Tests
 */
class ExtensionValidatorTest extends BaseTestCase
{
    const RULE = 'extension,png;jpg;gif';

    public function testItSuccessesWhenExtensionMatches()
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

    public function testItFailsWhenExtensionDoesntMatch()
    {
        $input = [
            'name' => 'document.pdf',
            'type' => 'application/pdf',
            'tmp_name' => '/tmp/phphjatI9',
            'error' => 0,
            'size' => 22068
        ];

        $this->assertNotTrue($this->validate(self::RULE, $input));
    }

    public function testItFailsWhenFileWasNotSuccessfullyUploaded()
    {
        $input = [
            'name' => 'screenshot.png',
            'type' => 'image/png',
            'tmp_name' => '/tmp/phphjatI9',
            'error' => 4,
            'size' => 22068
        ];

        $this->assertNotTrue($this->validate(self::RULE, $input));
    }

}