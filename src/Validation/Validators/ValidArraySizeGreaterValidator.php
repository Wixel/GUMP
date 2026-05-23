<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Check if an input is an array and the size is greater than or equal to a specific value. */
final class ValidArraySizeGreaterValidator implements Validator
{
    public const EXAMPLE_PARAMETER = '1';

    public function rule(): string
    {
        return 'valid_array_size_greater';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $field = $context->field;
        $input = $context->input;

        if (!is_array($input[$field]) || count($input[$field]) < $context->params[0]) {
            return Result::fail();
        }

        return Result::pass();
    }
}
