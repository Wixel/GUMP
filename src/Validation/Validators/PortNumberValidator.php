<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate port number (1-65535). */
final class PortNumberValidator implements Validator
{
    public function rule(): string
    {
        return 'port_number';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return is_numeric($value) && $value >= 1 && $value <= 65535
            ? Result::pass()
            : Result::fail();
    }
}
