<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided input is a valid date (ISO 8601) or specify a custom format (optional). */
final class DateValidator implements Validator
{
    public const EXAMPLE_PARAMETER = 'd/m/Y';

    public function rule(): string
    {
        return 'date';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $params = $context->params;

        // Default
        if (count($params) === 0) {
            $timestamp = strtotime((string) $value);
            if ($timestamp === false) {
                return Result::fail();
            }

            $cdate1 = date('Y-m-d', $timestamp);
            $cdate2 = date('Y-m-d H:i:s', $timestamp);

            return !($cdate1 != $value && $cdate2 != $value)
                ? Result::pass()
                : Result::fail();
        }

        $date = \DateTime::createFromFormat($params[0], (string) $value);

        return !($date === false || $value != date($params[0], $date->getTimestamp()))
            ? Result::pass()
            : Result::fail();
    }
}
