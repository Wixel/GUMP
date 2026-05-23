<?php

declare(strict_types=1);

namespace GUMP\Filtering\Filters;

use GUMP\Filtering\Filter;

/** Remove spaces from the beginning and end of strings. */
final class TrimFilter implements Filter
{
    public function rule(): string
    {
        return 'trim';
    }

    public function apply(mixed $value, array $params = []): mixed
    {
        return trim((string) $value);
    }
}
