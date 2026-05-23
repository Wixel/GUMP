<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided value is a valid phone number. */
final class PhoneNumberValidator implements Validator
{
    public function rule(): string
    {
        return 'phone_number';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $regex = '/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i';

        return preg_match($regex, (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
