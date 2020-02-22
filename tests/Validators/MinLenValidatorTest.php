<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;
use Mockery as m;

/**
 * Class MinLenValidatorTest
 *
 * @package Tests
 */
class MinLenValidatorTest extends BaseTestCase
{
    public function testSuccessWhenEqualWithMbStrlen()
    {
        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnTrue();

        $this->assertTrue($this->validate('min_len,5', 'ñándú'));
    }

    public function testSuccessWhenMoreWithMbStrlen()
    {
        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnTrue();

        $this->assertTrue($this->validate('min_len,2', 'ñán'));
    }

    public function testErrorWhenLessWithMbStrlen()
    {
        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnTrue();

        $this->assertNotTrue($this->validate('min_len,2', 'ñ'));
    }

    public function testSuccessWhenEqualWithStrlen()
    {
        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnFalse();

        $this->assertTrue($this->validate('min_len,3', 'ña'));
        $this->assertTrue($this->validate('min_len,2', 'na'));
    }

    public function testSuccessWhenMoreWithStrlen()
    {
        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnFalse();

        $this->assertTrue($this->validate('min_len,2', 'nan'));
    }

    public function testErrorWhenLessWithStrlen()
    {
        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnFalse();

        $this->assertNotTrue($this->validate('min_len,2', 'n'));
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
        $this->helpersMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnTrue();

         $this->assertTrue($this->validate('min_len,2', ''));
    }
}