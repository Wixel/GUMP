<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided field value equals current field value. */
final class EqualsfieldValidator implements Validator
{
    public const EXAMPLE_PARAMETER = 'other_field_name';

    public function rule(): string
    {
        return 'equalsfield';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $input = $context->input;
        $field = $context->field;
        $params = $context->params;

        return $input[$field] === $input[$params[0]]
            ? Result::pass()
            : Result::fail();
    }
}
