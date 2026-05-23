<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Detect common SQL injection patterns. */
final class NoSqlInjectionValidator implements Validator
{
    public function rule(): string
    {
        return 'no_sql_injection';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        if (!is_string($value)) {
            return Result::pass();
        }

        $patterns = [
            '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|UNION)\b)/i',
            '/(\b(OR|AND)\s+\d+\s*=\s*\d+)/i',
            '/[\'";]/',
            '/(\-\-|\#)/',
            '/\/\*|\*\//',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return Result::fail();
            }
        }

        return Result::pass();
    }
}
