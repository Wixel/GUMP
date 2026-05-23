<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP;
use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Ensures the specified key value exists and is not empty (not null, not empty string, not empty array). */
final class RequiredValidator implements Validator
{
    public function rule(): string
    {
        return 'required';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return GUMP::is_empty($value) ? Result::fail() : Result::pass();
    }
}
