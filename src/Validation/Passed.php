<?php

declare(strict_types=1);

namespace GUMP\Validation;

final class Passed extends Result
{
    public function isValid(): bool
    {
        return true;
    }
}
