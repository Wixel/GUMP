<?php

declare(strict_types=1);

namespace GUMP\Filtering;

use Closure;

/**
 * Adapter that wraps a legacy callable filter
 * (signature: function(mixed $value, array $params): mixed)
 * so it can be registered alongside first-class Filter implementations.
 */
final class ClosureFilter implements Filter
{
    public function __construct(
        private readonly string $rule,
        private readonly Closure $callback,
    ) {
    }

    public function rule(): string
    {
        return $this->rule;
    }

    public function apply(mixed $value, array $params = []): mixed
    {
        return ($this->callback)($value, $params);
    }
}
