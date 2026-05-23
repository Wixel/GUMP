<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate hash format for specified algorithm. */
final class HashValidator implements Validator
{
    public const EXAMPLE_PARAMETER = 'Array';

    public function rule(): string
    {
        return 'hash';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $algorithm = $context->params[0] ?? 'md5';

        $patterns = [
            'md5' => '/^[a-f0-9]{32}$/i',
            'sha1' => '/^[a-f0-9]{40}$/i',
            'sha256' => '/^[a-f0-9]{64}$/i',
            'sha512' => '/^[a-f0-9]{128}$/i',
        ];

        $isValid = isset($patterns[$algorithm])
            && preg_match($patterns[$algorithm], (string) $value) > 0;

        return $isValid ? Result::pass() : Result::fail();
    }
}
