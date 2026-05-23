<?php

declare(strict_types=1);

namespace GUMP\Validation;

use RuntimeException;

final class UnknownRuleException extends RuntimeException
{
    public static function forRule(string $rule): self
    {
        return new self(sprintf("'%s' validator does not exist.", $rule));
    }
}
