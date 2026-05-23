<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate URL slug format. */
final class UrlSlugValidator implements Validator
{
    public function rule(): string
    {
        return 'url_slug';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
