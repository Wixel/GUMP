<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate currency code (ISO 4217). */
final class CurrencyCodeValidator implements Validator
{
    public function rule(): string
    {
        return 'currency_code';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $currencies = [
            'USD', 'EUR', 'GBP', 'JPY', 'AUD', 'CAD', 'CHF', 'CNY', 'SEK', 'NZD',
            'MXN', 'SGD', 'HKD', 'NOK', 'TRY', 'ZAR', 'BRL', 'INR', 'KRW', 'RUB',
        ];

        return in_array($value, $currencies, true) ? Result::pass() : Result::fail();
    }
}
