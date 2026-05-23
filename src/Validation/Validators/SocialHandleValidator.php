<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate social media handle format. */
final class SocialHandleValidator implements Validator
{
    public function rule(): string
    {
        return 'social_handle';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return preg_match('/^@?[A-Za-z0-9_]{1,15}$/', (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
