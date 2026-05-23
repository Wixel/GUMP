<?php

declare(strict_types=1);

namespace GUMP\Filtering;

final class FilterRegistry
{
    /** @var array<string, Filter> */
    private array $filters = [];

    public function register(Filter $filter): void
    {
        $this->filters[$filter->rule()] = $filter;
    }

    public function has(string $rule): bool
    {
        return isset($this->filters[$rule]);
    }

    public function get(string $rule): Filter
    {
        return $this->filters[$rule] ?? throw UnknownFilterException::forFilter($rule);
    }
}
