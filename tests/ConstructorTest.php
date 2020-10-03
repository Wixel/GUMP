<?php

namespace Tests;

use GUMP;
use Exception;
use Mockery as m;

/**
 * Class ConstructorTest
 *
 * @package Tests
 */
class ConstructorTest extends BaseTestCase
{
    public function testItSetsDefaultLanguageProperty()
    {
        $gump = new GUMP();

        $this->assertEquals('en', self::getPrivateField($gump, 'lang'));
    }

    public function testItSetsLanguagePropertyWhenSet()
    {
        $gump = new GUMP('es');

        $this->assertEquals('es', self::getPrivateField($gump, 'lang'));
    }

    public function testItThrowsExceptionWhenLanguageFileDoesntExist()
    {
        $this->helpersMock->shouldReceive('file_exists')
            ->once()
            ->andReturnFalse();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("'es' language is not supported.");

        $gump = new GUMP('es');
    }
}