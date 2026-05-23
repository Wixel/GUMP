<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate camelCase format. */
final class CamelCaseValidator implements Validator
{
    public function rule(): string
    {
        return 'camel_case';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return (!empty($value) && preg_match('/^[a-z][a-zA-Z0-9]*$/', (string) $value) > 0)
            ? Result::pass()
            : Result::fail();
    }
}
