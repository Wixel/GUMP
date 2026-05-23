<?php

declare(strict_types=1);

namespace GUMP\Validation;

use Closure;

/**
 * Adapter that wraps a legacy callable validator
 * (signature: function(string $field, array $input, array $params, mixed $value): bool)
 * so it can be registered alongside first-class Validator implementations.
 */
final class ClosureValidator implements Validator
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

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $result = ($this->callback)(
            $context->field,
            $context->input,
            $context->params,
            $value,
        );

        return $result === false ? Result::fail() : Result::pass();
    }
}
