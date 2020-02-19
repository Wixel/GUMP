<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;
use Tests\MockableGump;
use phpmock\MockBuilder;

use Mockery as m;

/**
 * Class UrlExistsValidatorTest
 *
 * @package Tests
 */
class UrlExistsValidatorTest extends BaseTestCase
{
    use \phpmock\phpunit\PHPMock;

    public function testWhenCheckdnsrrEqualsTrueIsSuccessful()
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('checkdnsrr')
            ->andReturnTrue();

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('idn_to_ascii')
            ->andReturnTrue();


        $externalMock->shouldReceive('checkdnsrr')
            ->once()
            ->with('google.es', 'A')
            ->andReturnTrue();

        $result = $this->gump->validate([
            'test' => 'https://google.es',
        ], [
            'test' => 'url_exists'
        ]);

        $this->assertTrue($result);
    }

    public function testWhenCheckdnsrrEqualsFalseIsFailure()
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('checkdnsrr')
            ->andReturnTrue();

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('idn_to_ascii')
            ->andReturnTrue();

        $externalMock->shouldReceive('checkdnsrr')
            ->once()
            ->with('google.es', 'A')
            ->andReturnFalse();

        $result = $this->gump->validate([
            'test' => 'https://google.es',
        ], [
            'test' => 'url_exists'
        ]);

        $this->assertNotTrue($result);
    }

    public function testWhenGethostbynameReturnsIpAddressIsSuccess()
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('checkdnsrr')
            ->andReturnFalse();

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('idn_to_ascii')
            ->andReturnFalse();

        $externalMock->shouldReceive('gethostbyname')
            ->once()
            ->andReturn('127.0.0.1');


        $result = $this->gump->validate([
            'test' => 'https://google.es',
        ], [
            'test' => 'url_exists'
        ]);

        $this->assertTrue($result);
    }

    public function testWhenGethostbynameReturnsUrlIsFailure()
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('checkdnsrr')
            ->andReturnFalse();

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('idn_to_ascii')
            ->andReturnFalse();

        $externalMock->shouldReceive('gethostbyname')
            ->once()
            ->andReturn('google.es');

        $result = $this->gump->validate([
            'test' => 'https://google.es',
        ], [
            'test' => 'url_exists'
        ]);

        $this->assertNotTrue($result);
    }
}