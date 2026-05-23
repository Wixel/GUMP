<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Enhanced XSS detection beyond basic sanitize_string. */
final class NoXssValidator implements Validator
{
    public function rule(): string
    {
        return 'no_xss';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $patterns = [
            '/<script[^>]*>.*?<\/script>/is',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<iframe[^>]*>.*?<\/iframe>/is',
            '/<object[^>]*>.*?<\/object>/is',
            '/<embed[^>]*>/i',
            '/expression\s*\(/i',
            '/vbscript:/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, (string) $value)) {
                return Result::fail();
            }
        }

        return Result::pass();
    }
}
