<?php

namespace Tests;

use Exception;
use GUMP;
use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

class RegisterValidatorTest extends BaseTestCase
{
    public function testStaticRegisterValidatorMakesItAvailableGlobally(): void
    {
        GUMP::register_validator(new class () implements Validator {
            public function rule(): string
            {
                return 'must_be_seven';
            }
            public function validate(mixed $value, ValidationContext $context): Result
            {
                return $value === 7 ? Result::pass() : Result::fail();
            }
        });

        $this->assertTrue($this->gump->validate(['n' => 7], ['n' => 'must_be_seven']));
        $this->assertNotTrue($this->gump->validate(['n' => 8], ['n' => 'must_be_seven']));
    }

    public function testStaticRegisterValidatorRejectsDuplicates(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("'required' validator is already defined.");

        GUMP::register_validator(new class () implements Validator {
            public function rule(): string
            {
                return 'required';
            }
            public function validate(mixed $value, ValidationContext $context): Result
            {
                return Result::pass();
            }
        });
    }

    public function testLocalValidatorIsScopedToInstance(): void
    {
        $other = new GUMP();

        $this->gump->register_local_validator(new class () implements Validator {
            public function rule(): string
            {
                return 'must_be_local';
            }
            public function validate(mixed $value, ValidationContext $context): Result
            {
                return $value === 'local-only' ? Result::pass() : Result::fail();
            }
        });

        // The instance that registered it sees it.
        $this->assertTrue($this->gump->validate(['x' => 'local-only'], ['x' => 'must_be_local']));

        // A sibling GUMP instance does NOT see it (proves isolation).
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("'must_be_local' validator does not exist.");
        $other->validate(['x' => 'local-only'], ['x' => 'must_be_local']);
    }

    public function testLocalValidatorShadowsGlobalSameRuleName(): void
    {
        // Local override of 'required' that always fails — proves dispatch checks locals first.
        $this->gump->register_local_validator(new class () implements Validator {
            public function rule(): string
            {
                return 'required';
            }
            public function validate(mixed $value, ValidationContext $context): Result
            {
                return Result::fail();
            }
        });

        $result = $this->gump->validate(['x' => 'anything'], ['x' => 'required']);
        $this->assertIsArray($result, 'Local override should make required fail');

        // Global registry is untouched: a fresh GUMP still uses the built-in 'required'.
        $other = new GUMP();
        $this->assertTrue($other->validate(['x' => 'anything'], ['x' => 'required']));
    }
}
