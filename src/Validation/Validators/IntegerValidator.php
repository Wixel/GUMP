<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided value is a valid integer. */
final class IntegerValidator implements Validator
{
    public function rule(): string
    {
        return 'integer';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        if (is_bool($value) || $value === null) {
            return Result::fail();
        }

        return filter_var($value, FILTER_VALIDATE_INT) !== false
            ? Result::pass()
            : Result::fail();
    }
}
