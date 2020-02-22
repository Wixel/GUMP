<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;
use Mockery as m;

/**
 * Class MaxLenValidatorTest
 *
 * @package Tests
 */
class MaxLenValidatorTest extends BaseTestCase
{
    public function testSuccessWhenEqualWithMbStrlen()
    {
        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnTrue();

        $this->assertTrue($this->validate('max_len,5', 'ñándú'));
    }

    public function testSuccessWhenLessWithMbStrlen()
    {
        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnTrue();

        $this->assertTrue($this->validate('max_len,2', 'ñ'));
    }

    public function testErrorWhenMoreWithMbStrlen()
    {
        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnTrue();

        $this->assertNotTrue($this->validate('max_len,2', 'ñán'));
    }

    public function testSuccessWhenEqualWithStrlen()
    {
        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnFalse();

        $this->assertTrue($this->validate('max_len,3', 'ña'));
        $this->assertTrue($this->validate('max_len,2', 'na'));
    }

    public function testSuccessWhenLessWithStrlen()
    {
        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnFalse();

        $this->assertTrue($this->validate('max_len,2', 'n'));
    }

    public function testErrorWhenMoreWithStrlen()
    {
        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnFalse();

        $this->assertNotTrue($this->validate('max_len,2', 'nan'));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnTrue();

         $this->assertTrue($this->validate('max_len,2', ''));
    }
}