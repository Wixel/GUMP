<?php

declare(strict_types=1);

namespace GUMP\Filtering\Filters;

use GUMP\Filtering\Filter;

/** Replace noise words in a string. */
final class NoiseWordsFilter implements Filter
{
    public function rule(): string
    {
        return 'noise_words';
    }

    public function apply(mixed $value, array $params = []): mixed
    {
        $value = preg_replace('/\s\s+/u', chr(32), (string) $value);

        $value = " $value ";

        $words = explode(',', \GUMP::$en_noise_words);

        foreach ($words as $word) {
            $word = trim($word);

            $word = " $word "; // Normalize

            if (stripos($value, $word) !== false) {
                $value = str_ireplace($word, chr(32), $value);
            }
        }

        return trim($value);
    }
}
