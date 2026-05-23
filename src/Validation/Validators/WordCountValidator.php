<?php

declare(strict_types=1);

namespace GUMP\Validation\Validators;

use GUMP\Validation\Result;
use GUMP\Validation\ValidationContext;
use GUMP\Validation\Validator;

/** Validate word count within specified range. */
final class WordCountValidator implements Validator
{
    public const EXAMPLE_PARAMETER = 'min,10,max,500';

    public function rule(): string
    {
        return 'word_count';
    }

    public function validate(mixed $value, ValidationContext $context): Result
    {
        $wordCount = str_word_count((string) $value);
        $params = $context->params;
        $count = count($params);

        for ($i = 0; $i < $count; $i += 2) {
            if ($params[$i] === 'min' && isset($params[$i + 1])) {
                if ($wordCount < (int) $params[$i + 1]) {
                    return Result::fail();
                }
            }
            if ($params[$i] === 'max' && isset($params[$i + 1])) {
                if ($wordCount > (int) $params[$i + 1]) {
                    return Result::fail();
                }
            }
        }

        return Result::pass();
    }
}
