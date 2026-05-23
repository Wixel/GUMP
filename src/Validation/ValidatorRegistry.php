<?php

declare(strict_types=1);

namespace GUMP\Validation;

final class ValidatorRegistry
{
    /** @var array<string, Validator> */
    private array $validators = [];

    public function register(Validator $validator): void
    {
        $this->validators[$validator->rule()] = $validator;
    }

    public function has(string $rule): bool
    {
        return isset($this->validators[$rule]);
    }

    public function get(string $rule): Validator
    {
        return $this->validators[$rule] ?? throw UnknownRuleException::forRule($rule);
    }
}
