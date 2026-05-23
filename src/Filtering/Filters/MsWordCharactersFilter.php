<?php

declare(strict_types=1);

namespace GUMP\Filtering\Filters;

use GUMP\Filtering\Filter;

/** Convert MS Word special characters to web safe characters. */
final class MsWordCharactersFilter implements Filter
{
    public function rule(): string
    {
        return 'ms_word_characters';
    }

    public function apply(mixed $value, array $params = []): mixed
    {
        return str_replace(['“', '”', '‘', '’', '–', '…'], ['"', '"', "'", "'", '-', '...'], $value);
    }
}
