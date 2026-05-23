<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate domain name format (without protocol). */
final class DomainNameValidator implements Validator
{
    public function rule(): string
    {
        return 'domain_name';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return preg_match('/^(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,}$/', (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
