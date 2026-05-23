<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided value is a valid URL. */
final class ValidUrlValidator implements Validator
{
    public function rule(): string
    {
        return 'valid_url';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== false
            ? Result::pass()
            : Result::fail();
    }
}
