<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Check the uploaded file for extension. Doesn't check mime-type yet. */
final class ExtensionValidator implements Validator
{
    public const EXAMPLE_PARAMETER = 'png;jpg;gif';

    public function rule(): string
    {
        return 'extension';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $field = $context->field;
        $input = $context->input;
        $params = $context->params;

        if (!is_array($input[$field])) {
            return Result::fail();
        }

        // file is not required (empty upload)
        if ($input[$field]['error'] === 4 && $input[$field]['size'] === 0 && $input[$field]['name'] === '') {
            return Result::pass();
        }

        // when successfully uploaded we proceed to verify the extension
        if ($input[$field]['error'] === 0) {
            $params = array_map(static fn ($v) => trim(mb_strtolower((string) $v)), $params);

            $path_info = pathinfo($input[$field]['name']);
            $extension = $path_info['extension'] ?? null;

            return ($extension && in_array(mb_strtolower($extension), $params, true))
                ? Result::pass()
                : Result::fail();
        }

        return Result::fail();
    }
}
