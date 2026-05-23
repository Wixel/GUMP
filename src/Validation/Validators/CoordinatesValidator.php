<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate coordinates in lat,lng format. */
final class CoordinatesValidator implements Validator
{
    public function rule(): string
    {
        return 'coordinates';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        if (preg_match('/^(-?\d+\.?\d*),\s*(-?\d+\.?\d*)$/', (string) $value, $matches)) {
            $lat = (float) $matches[1];
            $lng = (float) $matches[2];

            return $lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180
                ? Result::pass()
                : Result::fail();
        }

        return Result::fail();
    }
}
