<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate postal code for specified country. */
final class PostalCodeValidator implements Validator
{
    public const EXAMPLE_PARAMETER = 'Array';

    public function rule(): string
    {
        return 'postal_code';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $country = $context->params[0] ?? 'US';

        $patterns = [
            'US' => '/^\d{5}(-\d{4})?$/',
            'CA' => '/^[A-Za-z]\d[A-Za-z] ?\d[A-Za-z]\d$/',
            'UK' => '/^[A-Za-z]{1,2}\d[A-Za-z\d]? ?\d[A-Za-z]{2}$/',
            'DE' => '/^\d{5}$/',
            'FR' => '/^\d{5}$/',
            'AU' => '/^\d{4}$/',
            'JP' => '/^\d{3}-\d{4}$/',
        ];

        return isset($patterns[$country]) && preg_match($patterns[$country], (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
