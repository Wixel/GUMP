<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate strong password with uppercase, lowercase, number and special character. */
final class StrongPasswordValidator implements Validator
{
    public function rule(): string
    {
        return 'strong_password';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        // At least 8 chars, 1 uppercase, 1 lowercase, 1 number, 1 special char
        return preg_match(
            '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            (string) $value
        ) > 0
            ? Result::pass()
            : Result::fail();
    }
}
