<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided value is a valid IPv6 address. */
final class ValidIpv6Validator implements Validator
{
    public function rule(): string
    {
        return 'valid_ipv6';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false
            ? Result::pass()
            : Result::fail();
    }
}
