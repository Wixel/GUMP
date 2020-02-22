<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;
use Mockery as m;

/**
 * Class UrlExistsValidatorTest
 *
 * @package Tests
 */
class UrlExistsValidatorTest extends BaseTestCase
{
    public function testWhenCheckdnsrrEqualsTrueIsSuccessful()
    {


        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('checkdnsrr')
            ->andReturnTrue();

        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('idn_to_ascii')
            ->andReturnTrue();


        $this->helpersMock->shouldReceive('checkdnsrr')
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


        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('checkdnsrr')
            ->andReturnTrue();

        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('idn_to_ascii')
            ->andReturnTrue();

        $this->helpersMock->shouldReceive('checkdnsrr')
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


        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('checkdnsrr')
            ->andReturnFalse();

        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('idn_to_ascii')
            ->andReturnFalse();

        $this->helpersMock->shouldReceive('gethostbyname')
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


        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('checkdnsrr')
            ->andReturnFalse();

        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('idn_to_ascii')
            ->andReturnFalse();

        $this->helpersMock->shouldReceive('gethostbyname')
            ->once()
            ->andReturn('google.es');

        $result = $this->gump->validate([
            'test' => 'https://google.es',
        ], [
            'test' => 'url_exists'
        ]);

        $this->assertNotTrue($result);
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {


        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('checkdnsrr')
            ->andReturnTrue();

        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('idn_to_ascii')
            ->andReturnTrue();

        $this->helpersMock->shouldReceive('gethostbyname')
            ->once()
            ->andReturn('google.es');

        $result = $this->gump->validate([
            'test' => '',
        ], [
            'test' => 'url_exists'
        ]);

        $this->assertTrue($result);
    }
}