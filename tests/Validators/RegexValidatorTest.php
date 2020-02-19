<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;
use Tests\MockableGump;
use phpmock\MockBuilder;

use Mockery as m;

/**
 * Class RegexValidatorTest
 *
 * @package Tests
 */
class RegexValidatorTest extends BaseTestCase
{
    use \phpmock\phpunit\PHPMock;

    public function testExpressionMatchesIsSuccess()
    {
        $result = $this->gump->validate([
            'test' => 'test-123',
        ], [
            'test' => 'regex,/test-[0-9]{3}/'
        ]);

        $this->assertTrue($result);
    }

    public function testExpressionDoesntMatchIsFailure()
    {
        $result = $this->gump->validate([
            'test' => 'test-12',
        ], [
            'test' => 'regex,/test-[0-9]{3}/'
        ]);

        $this->assertNotTrue($result);
    }
}