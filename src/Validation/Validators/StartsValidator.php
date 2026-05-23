<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided value starts with param. */
final class StartsValidator implements Validator
{
    public const EXAMPLE_PARAMETER = 'Z';

    public function rule(): string
    {
        return 'starts';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return strpos((string) $value, (string) $context->params[0]) === 0
            ? Result::pass()
            : Result::fail();
    }
}
