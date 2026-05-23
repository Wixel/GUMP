<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate base64 encoded data. */
final class Base64Validator implements Validator
{
    public function rule(): string
    {
        return 'base64';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        if (!is_string($value)) {
            return Result::fail();
        }

        $decoded = base64_decode($value, true);
        if ($decoded === false) {
            return Result::fail();
        }

        return base64_encode($decoded) === $value ? Result::pass() : Result::fail();
    }
}
