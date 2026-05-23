<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate that number is prime. */
final class PrimeValidator implements Validator
{
    public function rule(): string
    {
        return 'prime';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        if (!is_numeric($value)) {
            return Result::fail();
        }

        $num = (int) $value;
        if ($num < 2) {
            return Result::fail();
        }
        if ($num === 2) {
            return Result::pass();
        }
        if ($num % 2 === 0) {
            return Result::fail();
        }

        for ($i = 3; $i <= sqrt($num); $i += 2) {
            if ($num % $i === 0) {
                return Result::fail();
            }
        }

        return Result::pass();
    }
}
