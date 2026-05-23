<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided value length is more or equal to a specific value. */
final class MinLenValidator implements Validator
{
    public const EXAMPLE_PARAMETER = '4';

    public function rule(): string
    {
        return 'min_len';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return mb_strlen((string) $value) >= (int) $context->params[0]
            ? Result::pass()
            : Result::fail();
    }
}
