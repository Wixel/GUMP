<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Verify that a value is NOT contained within the pre-defined value set. Error message will NOT show the list of possible values. */
final class DoesntContainListValidator implements Validator
{
    public const EXAMPLE_PARAMETER = 'value1;value2';

    public function rule(): string
    {
        return 'doesnt_contain_list';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $contains = (new ContainsValidator())->validate($value, $context);

        return $contains->isValid() ? Result::fail() : Result::pass();
    }
}
