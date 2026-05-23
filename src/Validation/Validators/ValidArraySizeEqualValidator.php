<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Check if an input is an array and the size is equal to a specific value. */
final class ValidArraySizeEqualValidator implements Validator
{
    public const EXAMPLE_PARAMETER = '1';

    public function rule(): string
    {
        return 'valid_array_size_equal';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $field = $context->field;
        $input = $context->input;

        $isValid = !(!is_array($input[$field]) || count($input[$field]) != $context->params[0]);

        return $isValid ? Result::pass() : Result::fail();
    }
}
