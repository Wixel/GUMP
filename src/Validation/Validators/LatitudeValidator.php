<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate latitude coordinate (-90 to 90). */
final class LatitudeValidator implements Validator
{
    public function rule(): string
    {
        return 'latitude';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return is_numeric($value) && $value >= -90 && $value <= 90
            ? Result::pass()
            : Result::fail();
    }
}
