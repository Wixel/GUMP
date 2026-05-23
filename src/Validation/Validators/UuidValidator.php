<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate UUID format. */
final class UuidValidator implements Validator
{
    public function rule(): string
    {
        return 'uuid';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
