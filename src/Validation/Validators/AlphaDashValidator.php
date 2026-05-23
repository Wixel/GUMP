<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided value contains only alpha characters with dashed and underscores. */
final class AlphaDashValidator implements Validator
{
    private const ALPHA = 'a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝŸÑàáâãäåçèéêëìíîïðòóôõöùúûüýÿñ';

    public function rule(): string
    {
        return 'alpha_dash';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return preg_match('/^(['.self::ALPHA.'_-])+$/i', (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
