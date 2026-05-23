<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate timezone identifier. */
final class TimezoneValidator implements Validator
{
    public function rule(): string
    {
        return 'timezone';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return in_array($value, timezone_identifiers_list(), true)
            ? Result::pass()
            : Result::fail();
    }
}
