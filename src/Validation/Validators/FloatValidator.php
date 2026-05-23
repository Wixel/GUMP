<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided value is a valid float. */
final class FloatValidator implements Validator
{
    public function rule(): string
    {
        return 'float';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return filter_var($value, FILTER_VALIDATE_FLOAT) !== false
            ? Result::pass()
            : Result::fail();
    }
}
