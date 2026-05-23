<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate that date falls on a business day (Monday-Friday). */
final class BusinessDayValidator implements Validator
{
    public function rule(): string
    {
        return 'business_day';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $timestamp = strtotime((string) $value);
        if ($timestamp === false) {
            return Result::fail();
        }

        $dayOfWeek = (int) date('N', $timestamp);

        return ($dayOfWeek >= 1 && $dayOfWeek <= 5) ? Result::pass() : Result::fail();
    }
}
