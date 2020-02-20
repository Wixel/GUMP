<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;
use Mockery as m;

/**
 * Class ValidCcValidatorTest
 *
 * @package Tests
 */
class ValidCcValidatorTest extends BaseTestCase
{
    private const RULE = 'valid_cc';

    /**
     * @dataProvider successProvider
     */
    public function testSuccessWithMbStrlen($input)
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnTrue();

        $this->assertTrue($this->validate(self::RULE, $input));
    }

    /**
     * @dataProvider successProvider
     */
    public function testSuccessWithStrlen($input)
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnFalse();

        $this->assertTrue($this->validate(self::RULE, $input));
    }

    public function successProvider()
    {
        return [
            ['5105105105105100'],
        ];
    }

    /**
     * @dataProvider errorProvider
     */
    public function testErrorWithMbStrlen($input)
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnTrue();

        $this->assertNotTrue($this->validate(self::RULE, $input));
    }

    /**
     * @dataProvider errorProvider
     */
    public function testErrorWithStrlen($input)
    {
        $externalMock = m::mock('overload:GUMP\Helpers');

        $externalMock->shouldReceive('functionExists')
            ->once()
            ->with('mb_strlen')
            ->andReturnFalse();

        $this->assertNotTrue($this->validate(self::RULE, $input));
    }

    public function errorProvider()
    {
        return [
            [ '5105105105105101' ],
            [ '1212121212121212' ],
            [ 'text' ],
        ];
    }
}