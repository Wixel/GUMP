<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the file was successfully uploaded. */
final class RequiredFileValidator implements Validator
{
    public function rule(): string
    {
        return 'required_file';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $field = $context->field;
        $input = $context->input;

        return (isset($input[$field]) && is_array($input[$field]) && $input[$field]['error'] === 0)
            ? Result::pass()
            : Result::fail();
    }
}
