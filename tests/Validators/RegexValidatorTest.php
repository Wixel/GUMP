<?php

namespace Tests\Validators;

use GUMP;
use Exception;
use Tests\BaseTestCase;

use Mockery as m;

/**
 * Class RegexValidatorTest
 *
 * @package Tests
 */
class RegexValidatorTest extends BaseTestCase
{
    private const RULE = 'regex,/test-[0-9]{3}/';

    public function testExpressionMatchesIsSuccess()
    {
        $result = $this->gump->validate([
            'test' => 'test-123',
        ], [
            'test' => self::RULE
        ]);

        $this->assertTrue($result);
    }

    public function testExpressionDoesntMatchIsFailure()
    {
        $result = $this->gump->validate([
            'test' => 'test-12',
        ], [
            'test' => self::RULE
        ]);

        $this->assertNotTrue($result);
    }

    public function testWhenInputIsEmptyAndNotRequiredIsSuccess()
    {
         $this->assertTrue($this->validate(self::RULE, ''));
    }
}