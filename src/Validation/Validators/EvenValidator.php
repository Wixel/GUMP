<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate that number is even. */
final class EvenValidator implements Validator
{
    public function rule(): string
    {
        return 'even';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return (is_numeric($value) && (int) $value % 2 === 0)
            ? Result::pass()
            : Result::fail();
    }
}
