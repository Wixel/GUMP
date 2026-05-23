<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided value is a valid JSON string. */
final class ValidJsonStringValidator implements Validator
{
    public function rule(): string
    {
        return 'valid_json_string';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $isValid = is_string($value)
            && is_array(json_decode($value, true))
            && (json_last_error() == JSON_ERROR_NONE);

        return $isValid ? Result::pass() : Result::fail();
    }
}
