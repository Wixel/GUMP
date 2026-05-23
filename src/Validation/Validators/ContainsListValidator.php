<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Verify that a value is contained within the pre-defined value set. Error message will NOT show the list of possible values. */
final class ContainsListValidator implements Validator
{
    public const EXAMPLE_PARAMETER = 'value1;value2';

    public function rule(): string
    {
        return 'contains_list';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return (new ContainsValidator())->validate($value, $context);
    }
}
