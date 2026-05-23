<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate RGB color format. */
final class RgbColorValidator implements Validator
{
    public function rule(): string
    {
        return 'rgb_color';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        if (preg_match('/^rgb\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)$/i', (string) $value, $matches)) {
            $r = (int) $matches[1];
            $g = (int) $matches[2];
            $b = (int) $matches[3];

            return ($r >= 0 && $r <= 255 && $g >= 0 && $g <= 255 && $b >= 0 && $b <= 255)
                ? Result::pass()
                : Result::fail();
        }

        return Result::fail();
    }
}
