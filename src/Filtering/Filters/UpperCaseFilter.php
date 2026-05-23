<?php

declare(strict_types=1);

namespace GUMP\Filtering\Filters;

use GUMP\Filtering\Filter;

/** Converts to uppercase. */
final class UpperCaseFilter implements Filter
{
    public function rule(): string
    {
        return 'upper_case';
    }

    public function apply(mixed $value, array $params = []): mixed
    {
        return mb_strtoupper((string) $value);
    }
}
