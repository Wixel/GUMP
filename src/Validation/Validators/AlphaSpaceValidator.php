<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Determine if the provided value contains only alpha characters with spaces. */
final class AlphaSpaceValidator implements Validator
{
    private const ALPHA = 'a-zÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖßÙÚÛÜÝŸÑàáâãäåçèéêëìíîïðòóôõöùúûüýÿñ';

    public function rule(): string
    {
        return 'alpha_space';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        return preg_match('/^(['.self::ALPHA.'\s])+$/i', (string) $value) > 0
            ? Result::pass()
            : Result::fail();
    }
}
