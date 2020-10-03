<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;
use Mockery as m;

/**
 * Class ValidTwitterValidatorTest
 *
 * @package Tests
 */
class ValidTwitterValidatorTest extends BaseTestCase
{
    const RULE = 'valid_twitter';

    public function testWhenReasonIsTakenIsSuccess()
    {
        $this->helpersMock->shouldReceive('file_get_contents')
            ->once()
            ->with('http://twitter.com/users/username_available?username=filisdev')
            ->andReturn('{"reason":"taken"}');

        $result = $this->gump->validate([
            'test' => 'filisdev',
        ], [
            'test' => self::RULE
        ]);

        $this->assertTrue($result);
    }

    public function testWhenReasonIsNotTakenFails()
    {
        $this->helpersMock->shouldReceive('file_get_contents')
            ->once()
            ->with('http://twitter.com/users/username_available?username=filisdev')
            ->andReturn('{"reason":"available"}');

        $result = $this->gump->validate([
            'test' => 'filisdev',
        ], [
            'test' => self::RULE
        ]);

        $this->assertNotTrue($result);
    }

    public function testWhenInvalidJsonThrowsException()
    {
        $this->helpersMock->shouldReceive('file_get_contents')
            ->once()
            ->with('http://twitter.com/users/username_available?username=filisdev')
            ->andReturn('{"notReasonAnymore":"available"}');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Twitter JSON response changed. Please report this on GitHub.');

        $result = $this->gump->validate([
            'test' => 'filisdev',
        ], [
            'test' => self::RULE
        ]);

        $this->assertNotTrue($result);
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate(self::RULE, ''));
    }
}