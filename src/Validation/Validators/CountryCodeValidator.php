<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate country code (ISO 3166). */
final class CountryCodeValidator implements Validator
{
    public function rule(): string
    {
        return 'country_code';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return preg_match('/^[A-Z]{2}$/', (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
