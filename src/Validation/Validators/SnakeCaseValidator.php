<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate snake_case format. */
final class SnakeCaseValidator implements Validator
{
    public function rule(): string
    {
        return 'snake_case';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return preg_match('/^[a-z][a-z0-9_]*$/', (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
