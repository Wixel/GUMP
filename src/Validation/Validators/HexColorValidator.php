<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate hexadecimal color code. */
final class HexColorValidator implements Validator
{
    public function rule(): string
    {
        return 'hex_color';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
