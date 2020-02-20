<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;
use Mockery as m;

/**
 * Class ExactLenValidatorTest
 *
 * @package Tests
 */
class ExactLenValidatorTest extends BaseTestCase
{
    public function testSuccessWhenEqualWithMbStrlen()
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnTrue();

        $this->assertTrue($this->validate('exact_len,5', 'ñándú'));
    }

    public function testErrorWhenMoreWithMbStrlen()
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnTrue();

        $this->assertNotTrue($this->validate('exact_len,2', 'ñán'));
    }

    public function testErrorWhenLessWithMbStrlen()
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnTrue();

        $this->assertNotTrue($this->validate('exact_len,2', 'ñ'));
    }

    public function testSuccessWhenEqualWithStrlen()
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnFalse();

        $this->assertTrue($this->validate('exact_len,3', 'ña'));
        $this->assertTrue($this->validate('exact_len,2', 'na'));
    }

    public function testErrorWhenMoreWithStrlen()
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnFalse();

        $this->assertNotTrue($this->validate('exact_len,2', 'nan'));
    }

    public function testErrorWhenLessWithStrlen()
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnFalse();

        $this->assertNotTrue($this->validate('exact_len,2', 'n'));
    }
}