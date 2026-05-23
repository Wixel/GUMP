<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the input is a valid human name. */
final class ValidNameValidator implements Validator
{
    public function rule(): string
    {
        return 'valid_name';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return preg_match("/^([a-z \p{L} '-])+$/i", (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
