<?php

declare(strict_types=1);

namespace GUMP\Validation;

abstract class Result
{
    public static function pass(): self
    {
        return new Passed();
    }

    public static function fail(?string $reason = null): self
    {
        return new Failed($reason);
    }

    abstract public function isValid(): bool;

    public function reason(): ?string
    {
        return null;
    }
}
