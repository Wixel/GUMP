<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate longitude coordinate (-180 to 180). */
final class LongitudeValidator implements Validator
{
    public function rule(): string
    {
        return 'longitude';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return is_numeric($value) && $value >= -180 && $value <= 180
            ? Result::pass()
            : Result::fail();
    }
}
