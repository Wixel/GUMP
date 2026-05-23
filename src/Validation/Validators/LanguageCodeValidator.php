<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate language code (ISO 639). */
final class LanguageCodeValidator implements Validator
{
    public function rule(): string
    {
        return 'language_code';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        // ISO 639-1 (2 letter) or 639-1 with country code (en-US)
        return preg_match('/^[a-z]{2}(-[A-Z]{2})?$/', (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
