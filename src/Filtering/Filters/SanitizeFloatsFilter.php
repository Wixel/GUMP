<?php

declare(strict_types=1);

namespace GUMP\Filtering\Filters;

use GUMP\Filtering\Filter;

/** Sanitize the string by removing illegal characters from float numbers. */
final class SanitizeFloatsFilter implements Filter
{
    public function rule(): string
    {
        return 'sanitize_floats';
    }

    public function apply(mixed $value, array $params = []): mixed
    {
        return filter_var((string) $value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
}
