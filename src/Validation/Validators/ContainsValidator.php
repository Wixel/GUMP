<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Verify that a value is contained within the pre-defined value set. */
final class ContainsValidator implements Validator
{
    public const EXAMPLE_PARAMETER = 'one;two;use array format if one of the values contains semicolons';

    public function rule(): string
    {
        return 'contains';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $needle = mb_strtolower(trim((string) $value));

        $haystack = array_map(
            static fn ($v) => mb_strtolower(trim((string) $v)),
            $context->params,
        );

        return in_array($needle, $haystack, true) ? Result::pass() : Result::fail();
    }
}
