<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Custom regex validator. */
final class RegexValidator implements Validator
{
    public const EXAMPLE_PARAMETER = '/test-[0-9]{3}/';

    public function rule(): string
    {
        return 'regex';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return preg_match($context->params[0], (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
