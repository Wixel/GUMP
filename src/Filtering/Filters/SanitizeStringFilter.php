<?php

declare(strict_types=1);

namespace GUMP\Filtering\Filters;

use GUMP\Filtering\Filter;

/** Sanitize the string by removing any script tags. */
final class SanitizeStringFilter implements Filter
{
    public function rule(): string
    {
        return 'sanitize_string';
    }

    public function apply(mixed $value, array $params = []): mixed
    {
        return \GUMP::polyfill_filter_var_string($value);
    }
}
