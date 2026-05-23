<?php

declare(strict_types=1);

namespace GUMP\Validation;

final class ValidationContext
{
    /**
     * @param array<string, mixed> $input
     * @param array<int, mixed>    $params
     */
    public function __construct(
        public readonly string $field,
        public readonly array $input,
        public readonly array $params,
    ) {
    }
}
