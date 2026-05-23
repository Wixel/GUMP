<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided value contains only alpha-numeric characters. */
final class AlphaNumericValidator implements Validator
{
    private const ALPHA = 'a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝŸÑàáâãäåçèéêëìíîïðòóôõöùúûüýÿñ';

    public function rule(): string
    {
        return 'alpha_numeric';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return preg_match('/^(['.self::ALPHA.'0-9])+$/i', (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
