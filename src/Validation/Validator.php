<?php

declare(strict_types=1);

namespace GUMP\Validation;

interface Validator
{
    /**
     * Rule identifier used in rule strings, e.g. 'contains', 'min_len'.
     */
    public function rule(): string;

    /**
     * Validate a single value within its surrounding input context.
     */
    public function validate(mixed $value, ValidationContext $context): Result;
}
