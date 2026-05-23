<?php

declare(strict_types=1);

namespace GUMP\Filtering\Filters;

use GUMP\Filtering\Filter;

/** Remove all known punctuation from a string. */
final class RmpunctuationFilter implements Filter
{
    public function rule(): string
    {
        return 'rmpunctuation';
    }

    public function apply(mixed $value, array $params = []): mixed
    {
        return preg_replace("/(?![.=$'€%-])\p{P}/u", '', (string) $value);
    }
}
