<?php

declare(strict_types=1);

namespace GUMP\Filtering\Filters;

use GUMP\Filtering\Filter;

/** Converts to lowercase. */
final class LowerCaseFilter implements Filter
{
    public function rule(): string
    {
        return 'lower_case';
    }

    public function apply(mixed $value, array $params = []): mixed
    {
        return mb_strtolower((string) $value);
    }
}
