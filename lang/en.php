<?php

return array(
    'required'                 => 'The {field} field is required',
    'valid_email'              => 'The {field} field must be a valid email address',
    'max_len'                  => 'The {field} field needs to be {param} characters or less',
    'min_len'                  => 'The {field} field needs to be at least {param} characters',
    'exact_len'                => 'The {field} field needs to be exactly {param} characters',
    'between_len'              => 'The {field} field needs to be between {param[0]} and {param[1]} characters',
    'alpha'                    => 'The {field} field may only contain letters',
    'alpha_numeric'            => 'The {field} field may only contain letters and numbers',
    'alpha_numeric_space'      => 'The {field} field may only contain letters, numbers and spaces',
    'alpha_numeric_dash'       => 'The {field} field may only contain letters, numbers, dashes and underscores',
    'alpha_dash'               => 'The {field} field may only contain letters, dashes and underscores',
    'alpha_space'              => 'The {field} field may only contain letters and spaces',
    'numeric'                  => 'The {field} field must be a number',
    'integer'                  => 'The {field} field must be a number without a decimal',
    'boolean'                  => 'The {field} field has to be either true or false',
    'float'                    => 'The {field} field must be a number with a decimal point (float)',
    'valid_url'                => 'The {field} field has to be a URL',
    'url_exists'               => 'The {field} URL does not exist',
    'valid_ip'                 => 'The {field} field needs to be a valid IP address',
    'valid_ipv4'               => 'The {field} field needs to contain a valid IPv4 address',
    'valid_ipv6'               => 'The {field} field needs to contain a valid IPv6 address',
    'guidv4'                   => 'The {field} field needs to contain a valid GUID',
    'valid_cc'                 => 'The {field} is not a valid credit card number',
    'valid_name'               => 'The {field} should be a full name',
    'contains'                 => 'The {field} can only be one of the following: {param}',
    'contains_list'            => 'The {field} is not a valid option',
    'doesnt_contain_list'      => 'The {field} field contains a value that is not accepted',
    'street_address'           => 'The {field} field needs to be a valid street address',
    'date'                     => 'The {field} must be a valid date',
    'min_numeric'              => 'The {field} field needs to be a numeric value, equal to, or higher than {param}',
    'max_numeric'              => 'The {field} field needs to be a numeric value, equal to, or lower than {param}',
    'min_age'                  => 'The {field} field needs to have an age greater than or equal to {param}',
    'starts'                   => 'The {field} field needs to start with {param}',
    'extension'                => 'The {field} field can only have one of the following extensions: {param}',
    'required_file'            => 'The {field} field is required',
    'equalsfield'              => 'The {field} field does not equal {param} field',
    'iban'                     => 'The {field} field needs to contain a valid IBAN',
    'phone_number'             => 'The {field} field needs to be a valid Phone Number',
    'regex'                    => 'The {field} field needs to contain a value with valid format',
    'valid_json_string'        => 'The {field} field needs to contain a valid JSON format string',
    'valid_array_size_greater' => 'The {field} fields needs to be an array with a size, equal to, or higher than {param}',
    'valid_array_size_lesser'  => 'The {field} fields needs to be an array with a size, equal to, or lower than {param}',
    'valid_array_size_equal'   => 'The {field} fields needs to be an array with a size equal to {param}',

    // Security validators
    'strong_password'          => 'The {field} must contain at least 8 characters with uppercase, lowercase, number and special character',
    'jwt_token'                => 'The {field} must be a valid JWT token format',
    'hash'                     => 'The {field} must be a valid {param} hash',
    'no_sql_injection'         => 'The {field} contains potential SQL injection patterns',
    'no_xss'                   => 'The {field} contains potential XSS patterns',

    // Modern web validators
    'uuid'                     => 'The {field} must be a valid UUID',
    'base64'                   => 'The {field} must be valid base64 encoded data',
    'hex_color'                => 'The {field} must be a valid hexadecimal color code (e.g., #FF0000)',
    'rgb_color'                => 'The {field} must be a valid RGB color format (e.g., rgb(255,0,0))',
    'timezone'                 => 'The {field} must be a valid timezone identifier',
    'language_code'            => 'The {field} must be a valid language code (e.g., en, en-US)',
    'country_code'             => 'The {field} must be a valid country code (e.g., US, CA)',
    'currency_code'            => 'The {field} must be a valid currency code (e.g., USD, EUR)',

    // Network validators
    'mac_address'              => 'The {field} must be a valid MAC address format',
    'domain_name'              => 'The {field} must be a valid domain name',
    'port_number'              => 'The {field} must be a valid port number (1-65535)',
    'social_handle'            => 'The {field} must be a valid social media handle format',

    // Geographic validators
    'latitude'                 => 'The {field} must be a valid latitude (-90 to 90)',
    'longitude'                => 'The {field} must be a valid longitude (-180 to 180)',
    'postal_code'              => 'The {field} must be a valid postal code for {param}',
    'coordinates'              => 'The {field} must be valid coordinates in lat,lng format',

    // Enhanced date/time validators
    'future_date'              => 'The {field} must be a future date',
    'past_date'                => 'The {field} must be a past date',
    'business_day'             => 'The {field} must fall on a business day (Monday-Friday)',
    'valid_time'               => 'The {field} must be a valid time format (HH:MM or HH:MM:SS)',
    'date_range'               => 'The {field} must be a date between {param[0]} and {param[1]}',

    // Mathematical validators
    'even'                     => 'The {field} must be an even number',
    'odd'                      => 'The {field} must be an odd number',
    'prime'                    => 'The {field} must be a prime number',

    // Content validators
    'word_count'               => 'The {field} word count does not meet requirements',
    'camel_case'               => 'The {field} must be in camelCase format',
    'snake_case'               => 'The {field} must be in snake_case format',
    'url_slug'                 => 'The {field} must be a valid URL slug format',
);
