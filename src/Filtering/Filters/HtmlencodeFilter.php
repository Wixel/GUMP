<?php

declare(strict_types=1);

namespace GUMP\Filtering\Filters;

use GUMP\Filtering\Filter;

/** Sanitize the string by converting HTML characters to their HTML entities. */
final class HtmlencodeFilter implements Filter
{
    public function rule(): string
    {
        return 'htmlencode';
    }

    public function apply(mixed $value, array $params = []): mixed
    {
        return filter_var((string) $value, FILTER_SANITIZE_SPECIAL_CHARS);
    }
}
