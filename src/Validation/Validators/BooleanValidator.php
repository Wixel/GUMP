<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided value is a valid boolean. */
final class BooleanValidator implements Validator
{
    public const EXAMPLE_PARAMETER = 'strict';

    public function rule(): string
    {
        return 'boolean';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        if (isset($context->params[0]) && $context->params[0] === 'strict') {
            return in_array($value, [true, false], true) ? Result::pass() : Result::fail();
        }

        $booleans = array_merge(\GUMP::$trues, \GUMP::$falses);

        return in_array($value, $booleans, true) ? Result::pass() : Result::fail();
    }
}
