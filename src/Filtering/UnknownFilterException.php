<?php

declare(strict_types=1);

namespace GUMP\Filtering;

use RuntimeException;

final class UnknownFilterException extends RuntimeException
{
    public static function forFilter(string $filter): self
    {
        return new self(sprintf("'%s' filter does not exist.", $filter));
    }
}
