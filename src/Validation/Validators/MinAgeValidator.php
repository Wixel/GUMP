<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use DateTime;
use GUMP\EnvHelpers;
use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided input meets age requirement (ISO 8601). Input should be a date (Y-m-d). */
final class MinAgeValidator implements Validator
{
    public const EXAMPLE_PARAMETER = '18';

    public function rule(): string
    {
        return 'min_age';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $inputDatetime = new DateTime(EnvHelpers::date('Y-m-d', strtotime((string) $value)));
        $todayDatetime = new DateTime(EnvHelpers::date('Y-m-d'));

        $interval = $todayDatetime->diff($inputDatetime);
        $yearsPassed = $interval->y;

        return $yearsPassed >= $context->params[0]
            ? Result::pass()
            : Result::fail();
    }
}
