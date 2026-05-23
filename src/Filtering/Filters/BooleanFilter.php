<?php

declare(strict_types=1);

namespace GUMP\Filtering\Filters;

use GUMP\Filtering\Filter;

/** Converts ['1', 1, 'true', true, 'yes', 'on'] to true, anything else is false ('on' is useful for form checkboxes). */
final class BooleanFilter implements Filter
{
    public function rule(): string
    {
        return 'boolean';
    }

    public function apply(mixed $value, array $params = []): mixed
    {
        if (in_array($value, \GUMP::$trues, true)) {
            return true;
        }

        return false;
    }
}
