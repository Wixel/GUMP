<?php

declare(strict_types=1);

namespace GUMP\Filtering;

interface Filter
{
    /**
     * Filter identifier used in rule strings, e.g. 'trim', 'sanitize_string'.
     */
    public function rule(): string;

    /**
     * Transform a value. Implementations may return any type the caller expects.
     *
     * @param array<int, mixed> $params
     */
    public function apply(mixed $value, array $params = []): mixed;
}
