<?php

declare(strict_types=1);

namespace GUMP\Filtering\Filters;

use GUMP\Filtering\Filter;

/** Convert the provided numeric value to a whole number. */
final class WholeNumberFilter implements Filter
{
    public function rule(): string
    {
        return 'whole_number';
    }

    public function apply(mixed $value, array $params = []): mixed
    {
        return intval($value);
    }
}
