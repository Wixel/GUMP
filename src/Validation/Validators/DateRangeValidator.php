<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate date falls within specified range. */
final class DateRangeValidator implements Validator
{
    public const EXAMPLE_PARAMETER = '2024-01-01;2024-12-31';

    public function rule(): string
    {
        return 'date_range';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        if (count($context->params) < 2) {
            return Result::fail();
        }

        $timestamp = strtotime((string) $value);
        $startTimestamp = strtotime((string) $context->params[0]);
        $endTimestamp = strtotime((string) $context->params[1]);

        if ($timestamp === false || $startTimestamp === false || $endTimestamp === false) {
            return Result::fail();
        }

        return ($timestamp >= $startTimestamp && $timestamp <= $endTimestamp)
            ? Result::pass()
            : Result::fail();
    }
}
