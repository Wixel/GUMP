<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate that date is in the past. */
final class PastDateValidator implements Validator
{
    public function rule(): string
    {
        return 'past_date';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $timestamp = strtotime((string) $value);

        return $timestamp !== false && $timestamp < time()
            ? Result::pass()
            : Result::fail();
    }
}
