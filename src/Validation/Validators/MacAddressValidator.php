<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate MAC address format. */
final class MacAddressValidator implements Validator
{
    public function rule(): string
    {
        return 'mac_address';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return preg_match('/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/', (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
