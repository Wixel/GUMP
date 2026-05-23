<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided value is a valid number or numeric string. */
final class NumericValidator implements Validator
{
    public function rule(): string
    {
        return 'numeric';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return is_numeric($value) ? Result::pass() : Result::fail();
    }
}
