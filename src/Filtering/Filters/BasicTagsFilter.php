<?php

declare(strict_types=1);

namespace GUMP\Filtering\Filters;

use GUMP\Filtering\Filter;

/** Filter out all HTML tags except the defined basic tags. */
final class BasicTagsFilter implements Filter
{
    public function rule(): string
    {
        return 'basic_tags';
    }

    public function apply(mixed $value, array $params = []): mixed
    {
        return strip_tags($value, \GUMP::$basic_tags);
    }
}
