<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided input is likely to be a street address using weak detection. */
final class StreetAddressValidator implements Validator
{
    public function rule(): string
    {
        return 'street_address';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        // Theory: 1 number, 1 or more spaces, 1 or more words
        $value = (string) $value;
        $has_letter = preg_match('/[a-zA-Z]/', $value);
        $has_digit = preg_match('/\d/', $value);
        $has_space = preg_match('/\s/', $value);

        return ($has_letter && $has_digit && $has_space)
            ? Result::pass()
            : Result::fail();
    }
}
