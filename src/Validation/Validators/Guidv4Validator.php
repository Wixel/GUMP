<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided value is a valid GUID v4. */
final class Guidv4Validator implements Validator
{
    public function rule(): string
    {
        return 'guidv4';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return preg_match(
            "/\{?[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}\}?$/",
            (string) $value
        ) > 0
            ? Result::pass()
            : Result::fail();
    }
}
