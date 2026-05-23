<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided value is a valid IBAN. */
final class IbanValidator implements Validator
{
    public function rule(): string
    {
        return 'iban';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $character = [
            'A' => 10, 'C' => 12, 'D' => 13, 'E' => 14, 'F' => 15, 'G' => 16,
            'H' => 17, 'I' => 18, 'J' => 19, 'K' => 20, 'L' => 21, 'M' => 22,
            'N' => 23, 'O' => 24, 'P' => 25, 'Q' => 26, 'R' => 27, 'S' => 28,
            'T' => 29, 'U' => 30, 'V' => 31, 'W' => 32, 'X' => 33, 'Y' => 34,
            'Z' => 35, 'B' => 11,
        ];

        $value = (string) $value;

        if (!preg_match("/\A[A-Z]{2}\d{2} ?[A-Z\d]{4}( ?\d{4}){1,} ?\d{1,4}\z/", $value)) {
            return Result::fail();
        }

        $iban = str_replace(' ', '', $value);
        $iban = substr($iban, 4).substr($iban, 0, 4);
        $iban = strtr($iban, $character);

        return bcmod($iban, '97') == 1 ? Result::pass() : Result::fail();
    }
}
