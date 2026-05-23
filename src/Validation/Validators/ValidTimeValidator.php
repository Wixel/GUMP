<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate time format (HH:MM:SS or HH:MM). */
final class ValidTimeValidator implements Validator
{
    public function rule(): string
    {
        return 'valid_time';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
