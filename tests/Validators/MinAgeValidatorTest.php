<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

use Mockery as m;

/**
 * Class MinAgeValidatorTest
 *
 * @package Tests
 */
class MinAgeValidatorTest extends BaseTestCase
{
    public function testSameDayAsBirthdayIsSuccess()
    {


        $this->helpersMock->shouldReceive('date')
            ->once()
            ->with('Y-m-d', 866419200)
            ->andReturn('1997-06-16');

        $this->helpersMock->shouldReceive('date')
            ->once()
            ->with('Y-m-d')
            ->andReturn('2020-06-16');


        $result = $this->gump->validate([
            'test' => '1997-06-16',
        ], [
            'test' => 'min_age,23'
        ]);

        $this->assertTrue($result);
    }

    public function testOneDayAfterBirthdayIsSuccess()
    {


        $this->helpersMock->shouldReceive('date')
            ->once()
            ->with('Y-m-d', 866419200)
            ->andReturn('1997-06-16');

        $this->helpersMock->shouldReceive('date')
            ->once()
            ->with('Y-m-d')
            ->andReturn('2020-06-17');

        $result = $this->gump->validate([
            'test' => '1997-06-16',
        ], [
            'test' => 'min_age,23'
        ]);

        $this->assertTrue($result);
    }

    public function testOneDayBeforeBirthdayFails()
    {


        $this->helpersMock->shouldReceive('date')
            ->once()
            ->with('Y-m-d', 866419200)
            ->andReturn('1997-06-16');

        $this->helpersMock->shouldReceive('date')
            ->once()
            ->with('Y-m-d')
            ->andReturn('2020-06-15');

        $result = $this->gump->validate([
            'test' => '1997-06-16',
        ], [
            'test' => 'min_age,23'
        ]);

        $this->assertNotTrue($result);
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
        $this->helpersMock = m::mock('overload:GUMP\EnvHelpers');

        $this->helpersMock->shouldReceive('date')
            ->once()
            ->with('Y-m-d', 866419200)
            ->andReturn('1997-06-16');

        $this->helpersMock->shouldReceive('date')
            ->once()
            ->with('Y-m-d')
            ->andReturn('2020-06-15');

        $result = $this->gump->validate([
            'test' => '',
        ], [
            'test' => 'min_age,23'
        ]);

        $this->assertTrue($result);
    }
}