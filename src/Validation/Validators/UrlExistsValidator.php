<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\EnvHelpers;
use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if a URL exists & is accessible. */
final class UrlExistsValidator implements Validator
{
    public function rule(): string
    {
        return 'url_exists';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $url = parse_url(mb_strtolower((string) $value));

        if (isset($url['host'])) {
            $url = $url['host'];
        }

        return EnvHelpers::checkdnsrr(idn_to_ascii($url, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46), 'A') !== false
            ? Result::pass()
            : Result::fail();
    }
}
