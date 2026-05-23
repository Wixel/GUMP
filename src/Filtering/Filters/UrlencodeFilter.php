<?php

declare(strict_types=1);

namespace GUMP\Filtering\Filters;

use GUMP\Filtering\Filter;

/** Sanitize the string by urlencoding characters. */
final class UrlencodeFilter implements Filter
{
    public function rule(): string
    {
        return 'urlencode';
    }

    public function apply(mixed $value, array $params = []): mixed
    {
        return filter_var((string) $value, FILTER_SANITIZE_ENCODED);
    }
}
