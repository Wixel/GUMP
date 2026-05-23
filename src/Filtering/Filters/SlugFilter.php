<?php

declare(strict_types=1);

namespace GUMP\Filtering\Filters;

use GUMP\Filtering\Filter;

/** Converts value to url-web-slugs. */
final class SlugFilter implements Filter
{
    public function rule(): string
    {
        return 'slug';
    }

    public function apply(mixed $value, array $params = []): mixed
    {
        $delimiter = '-';

        $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT', (string) $value);
        if ($transliterated === false) {
            $transliterated = (string) $value;
        }

        $noApostrophes = str_replace(["'"], '', $transliterated);
        $ampersandToAnd = preg_replace('/[&]/', 'and', $noApostrophes);
        $alnumAndDash = preg_replace('/[^A-Za-z0-9-]+/', $delimiter, $ampersandToAnd);
        $collapse = preg_replace('/[\s-]+/', $delimiter, $alnumAndDash);

        return mb_strtolower(trim($collapse, $delimiter));
    }
}
