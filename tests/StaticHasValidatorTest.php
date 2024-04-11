<?php

namespace Tests;

use GUMP;

class StaticHasValidatorTest extends BaseTestCase
{
    public function testHasValidatorWhenExists(): void
    {
        $validationRules = [
            'required',
            'contains',
            'contains_list',
            'doesnt_contain_list',
            'boolean',
            'valid_email',
            'max_len',
            'min_len',
            'exact_len',
            'between_len',
            'alpha',
            'alpha_numeric',
            'alpha_dash',
            'alpha_numeric_dash',
            'alpha_numeric_space',
            'alpha_space',
            'numeric',
            'integer',
            'float',
            'valid_url',
            'url_exists',
            'valid_ip',
            'valid_ipv4',
            'valid_ipv6',
            'valid_cc',
            'valid_name',
            'street_address',
            'iban',
            'date',
            'min_age',
            'max_numeric',
            'min_numeric',
            'starts',
            'required_file',
            'extension',
            'equalsfield',
            'guidv4',
            'phone_number',
            'regex',
            'valid_json_string',
            'valid_array_size_greater',
            'valid_array_size_lesser',
            'valid_array_size_equal',
        ];

        foreach ($validationRules as $rule) {
            $this->assertTrue(GUMP::has_validator($rule));
        }
    }

    public function testHasValidatorWhenNotExists(): void
    {
        $this->assertFalse(GUMP::has_validator('custom_rule'));
    }
}