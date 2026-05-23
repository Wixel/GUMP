<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate JWT token format. */
final class JwtTokenValidator implements Validator
{
    public function rule(): string
    {
        return 'jwt_token';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $parts = explode('.', (string) $value);
        if (count($parts) !== 3) {
            return Result::fail();
        }

        foreach ($parts as $part) {
            if (!preg_match('/^[A-Za-z0-9_-]+$/', $part)) {
                return Result::fail();
            }
        }

        return Result::pass();
    }
}
