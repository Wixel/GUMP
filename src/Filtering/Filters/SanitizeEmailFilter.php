<?php

declare(strict_types=1);

namespace GUMP\Filtering\Filters;

use GUMP\Filtering\Filter;

/** Sanitize the string by removing illegal characters from emails. */
final class SanitizeEmailFilter implements Filter
{
    public function rule(): string
    {
        return 'sanitize_email';
    }

    public function apply(mixed $value, array $params = []): mixed
    {
        return filter_var((string) $value, FILTER_SANITIZE_EMAIL);
    }
}
