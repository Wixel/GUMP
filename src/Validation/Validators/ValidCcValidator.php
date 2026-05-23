<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the input is a valid credit card number. */
final class ValidCcValidator implements Validator
{
    public function rule(): string
    {
        return 'valid_cc';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $number = preg_replace('/\D/', '', (string) $value);

        $number_length = mb_strlen($number);

        /**
         * Bail out if $number_length is 0.
         * This can be the case if a user has entered only alphabets
         *
         * @since 1.5
         */
        if ($number_length == 0) {
            return Result::fail();
        }

        $parity = $number_length % 2;

        $total = 0;

        for ($i = 0; $i < $number_length; ++$i) {
            $digit = $number[$i];

            if ($i % 2 == $parity) {
                $digit *= 2;

                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $total += $digit;
        }

        return $total % 10 == 0 ? Result::pass() : Result::fail();
    }
}
