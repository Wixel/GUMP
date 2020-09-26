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

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
        $result = $this->gump->validate([
            'test' => '',
        ], [
            'test' => 'url_exists'
        ]);

        $this->assertTrue($result);
    }
}