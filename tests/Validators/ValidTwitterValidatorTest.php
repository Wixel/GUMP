<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;
use Tests\MockableGump;
use phpmock\MockBuilder;

use Mockery as m;

/**
 * Class ValidTwitterValidatorTest
 *
 * @package Tests
 */
class ValidTwitterValidatorTest extends BaseTestCase
{
    use \phpmock\phpunit\PHPMock;

    public function testWhenReasonIsTakenIsSuccess()
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('file_get_contents')
            ->once()
            ->with('http://twitter.com/users/username_available?username=filisdev')
            ->andReturn('{"reason":"taken"}');

        $result = $this->gump->validate([
            'test' => 'filisdev',
        ], [
            'test' => 'valid_twitter'
        ]);

        $this->assertTrue($result);
    }

    public function testWhenReasonIsNotTakenFails()
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('file_get_contents')
            ->once()
            ->with('http://twitter.com/users/username_available?username=filisdev')
            ->andReturn('{"reason":"available"}');

        $result = $this->gump->validate([
            'test' => 'filisdev',
        ], [
            'test' => 'valid_twitter'
        ]);

        $this->assertNotTrue($result);
    }
}