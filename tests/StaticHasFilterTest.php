<?php

namespace Tests;

use GUMP;

class StaticHasFilterTest extends BaseTestCase
{
    public function testHasFilterWhenExists(): void
    {
        $filterRules = [
            // There are native filters
            'noise_words',
            'rmpunctuation',
            'urlencode',
            'htmlencode',
            'sanitize_email',
            'sanitize_numbers',
            'sanitize_floats',
            'sanitize_string',
            'boolean',
            'basic_tags',
            'whole_number',
            'ms_word_characters',
            'lower_case',
            'upper_case',
            'slug',
            // These are built-in functions
            'trim',
            'strtoupper',
            'strtolower',
            'intval',
            'floatval',
        ];

        foreach ($filterRules as $filterRule) {
            $this->assertTrue(GUMP::has_filter($filterRule));
        }
    }

    public function testHasFilterWhenNotExists(): void
    {
        $this->assertFalse(GUMP::has_filter('custom_filter'));
    }
}