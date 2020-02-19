<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;
use Tests\MockableGump;
use phpmock\MockBuilder;

use Mockery as m;

/**
 * Class MinAgeValidatorTest
 *
 * @package Tests
 */
class MinAgeValidatorTest extends BaseTestCase
{
    use \phpmock\phpunit\PHPMock;

    public function testSameDayAsBirthdayIsSuccess()
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('date')
            ->once()
            ->with('Y-m-d', 866419200)
            ->andReturn('1997-06-16');

        $externalMock->shouldReceive('date')
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
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('date')
            ->once()
            ->with('Y-m-d', 866419200)
            ->andReturn('1997-06-16');

        $externalMock->shouldReceive('date')
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
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('date')
            ->once()
            ->with('Y-m-d', 866419200)
            ->andReturn('1997-06-16');

        $externalMock->shouldReceive('date')
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
}