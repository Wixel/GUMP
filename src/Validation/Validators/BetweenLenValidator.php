<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided value length is between min and max values. */
final class BetweenLenValidator implements Validator
{
    public const EXAMPLE_PARAMETER = '3;11';

    public function rule(): string
    {
        return 'between_len';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $minContext = new ValidationContext($context->field, $context->input, [$context->params[0]]);
        $maxContext = new ValidationContext($context->field, $context->input, [$context->params[1]]);

        if (!(new MinLenValidator())->validate($value, $minContext)->isValid()) {
            return Result::fail();
        }

        return (new MaxLenValidator())->validate($value, $maxContext)->isValid()
            ? Result::pass()
            : Result::fail();
    }
}
