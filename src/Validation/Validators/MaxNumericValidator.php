<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided numeric value is lower or equal to a specific value. */
final class MaxNumericValidator implements Validator
{
    public const EXAMPLE_PARAMETER = '50';

    public function rule(): string
    {
        return 'max_numeric';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $params = $context->params;

        return (is_numeric($value) && is_numeric($params[0]) && ($value <= $params[0]))
            ? Result::pass()
            : Result::fail();
    }
}
