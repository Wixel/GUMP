<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided numeric value is higher or equal to a specific value. */
final class MinNumericValidator implements Validator
{
    public const EXAMPLE_PARAMETER = '1';

    public function rule(): string
    {
        return 'min_numeric';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $params = $context->params;

        return (is_numeric($value) && is_numeric($params[0]) && ($value >= $params[0]))
            ? Result::pass()
            : Result::fail();
    }
}
