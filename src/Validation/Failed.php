<?php

declare(strict_types=1);

namespace GUMP\Validation;

final class Failed extends Result
{
    public function __construct(private readonly ?string $reason = null)
    {
    }

    public function isValid(): bool
    {
        return false;
    }

    public function reason(): ?string
    {
        return $this->reason;
    }
}
