# GUMP - A Fast PHP Data Validation & Filtering Library

[![Total Downloads](https://poser.pugx.org/wixel/gump/downloads)](https://packagist.org/packages/wixel/gump)
[![Latest Stable Version](https://poser.pugx.org/wixel/gump/v/stable)](https://packagist.org/packages/wixel/gump)
![Build Status](https://github.com/wixel/gump/actions/workflows/ci.yml/badge.svg)
[![Coverage Status](https://coveralls.io/repos/github/Wixel/GUMP/badge.svg?branch=master)](https://coveralls.io/github/Wixel/GUMP?branch=master)
[![License](https://poser.pugx.org/wixel/gump/license)](https://packagist.org/packages/wixel/gump)

## 🚀 Overview

GUMP is a standalone PHP data validation and filtering library that makes validating any data easy and painless without
the reliance on a framework. GUMP has been serving the PHP community since **2013** and is trusted by thousands of
developers worldwide.

### Key Features

- **🔒 Zero Dependencies** - Pure PHP, no external dependencies required
- **🌍 19 Languages** - Built-in internationalization support
- **⚡ High Performance** - Lightweight and fast validation processing
- **🔧 Extensible** - Easy to add custom validators and filters
- **📋 41 Validators** - Comprehensive set of validation rules out of the box
- **🛡️ Security Focused** - Built-in XSS protection and data sanitization
- **🎯 Framework Agnostic** - Works with any PHP project or framework
- **📱 Modern PHP** - Supports PHP 7.1 to 8.4+

## Table of Contents

- [Installation](#-installation)
- [Requirements](#-requirements)
- [Quick Start](#-quick-start)
- [Usage Examples](#-usage-examples)
- [Available Validators](#-available-validators)
- [Available Filters](#-available-filters)
- [Advanced Usage](#-advanced-usage)
- [Internationalization](#-internationalization)
- [Custom Validators & Filters](#-custom-validators--filters)
- [Configuration](#-configuration)
- [Testing](#-testing)
- [Contributing](#-contributing)
- [Security](#-security)
- [Changelog](#-changelog)
- [Support](#-support)
- [License](#-license)

## Installation

### Via Composer (Recommended)

```bash
composer require wixel/gump
```

### Manual Installation

1. Download the latest release from [GitHub Releases](https://github.com/wixel/gump/releases)
2. Extract and include `gump.class.php` in your project:

```php
require_once 'path/to/gump.class.php';
```

## Requirements

- **PHP**: 7.1, 7.2, 7.3, 7.4, 8.0, 8.1, 8.2, 8.3, 8.4+
- **Extensions**:
    - `ext-mbstring` - Multibyte string support
    - `ext-json` - JSON processing
    - `ext-intl` - Internationalization functions
    - `ext-bcmath` - Arbitrary precision mathematics
    - `ext-iconv` - Character encoding conversion

## Quick Start

### Simple Validation

```php
<?php
require_once 'vendor/autoload.php';

$is_valid = GUMP::is_valid([
    'username' => 'johndoe',
    'email'    => 'john@example.com',
    'age'      => '25'
], [
    'username' => 'required|alpha_numeric|min_len,3',
    'email'    => 'required|valid_email',
    'age'      => 'required|integer|min_numeric,18'
]);

if ($is_valid === true) {
    echo "✅ All data is valid!";
} else {
    // Display validation errors
    foreach ($is_valid as $error) {
        echo "❌ " . $error . "\n";
    }
}
```

### Simple Filtering

```php
$filtered = GUMP::filter_input([
    'username' => ' JohnDoe123 ',
    'bio'      => '<script>alert("xss")</script>Clean bio text'
], [
    'username' => 'trim|lower_case',
    'bio'      => 'trim|sanitize_string'
]);

// Result:
// $filtered['username'] = 'johndoe123'
// $filtered['bio'] = 'Clean bio text'
```

## Usage Examples

### Basic Validation with Custom Error Messages

```php
$gump = new GUMP();

// Set validation rules
$gump->validation_rules([
    'username'    => 'required|alpha_numeric|max_len,100|min_len,6',
    'password'    => 'required|max_len,100|min_len,8',
    'email'       => 'required|valid_email',
    'phone'       => 'required|phone_number',
    'website'     => 'valid_url',
    'birthday'    => 'required|date,Y-m-d|min_age,18'
]);

// Set custom error messages
$gump->set_fields_error_messages([
    'username' => [
        'required'  => 'Please enter a username',
        'min_len'   => 'Username must be at least 6 characters'
    ],
    'email' => [
        'required'    => 'Email address is required',
        'valid_email' => 'Please enter a valid email address'
    ]
]);

// Set filtering rules
$gump->filter_rules([
    'username' => 'trim|sanitize_string',
    'email'    => 'trim|sanitize_email',
    'phone'    => 'trim',
    'website'  => 'trim'
]);

$validated_data = $gump->run($_POST);

if ($gump->errors()) {
    // Handle validation errors
    $errors = $gump->get_readable_errors();
    foreach ($errors as $error) {
        echo "<div class='error'>{$error}</div>";
    }
} else {
    // Process validated and filtered data
    echo "User registered successfully!";
    var_dump($validated_data);
}
```

### File Upload Validation

```php
$is_valid = GUMP::is_valid(array_merge($_POST, $_FILES), [
    'profile_photo' => 'required_file|extension,jpg;jpeg;png;gif',
    'document'      => 'required_file|extension,pdf;doc;docx',
    'username'      => 'required|alpha_numeric'
]);

if ($is_valid !== true) {
    foreach ($is_valid as $error) {
        echo "Upload Error: {$error}\n";
    }
}
```

### Array and Nested Field Validation

```php
$data = [
    'user' => [
        'name'  => 'John Doe',
        'email' => 'john@example.com'
    ],
    'products' => [
        ['name' => 'Product 1', 'price' => 19.99],
        ['name' => 'Product 2', 'price' => 29.99]
    ],
    'tags' => ['php', 'validation', 'security']
];

$is_valid = GUMP::is_valid($data, [
    'user.name'        => 'required|valid_name',
    'user.email'       => 'required|valid_email',
    'products.*.name'  => 'required|min_len,3',
    'products.*.price' => 'required|float|min_numeric,0',
    'tags'             => 'required|valid_array_size_greater,0'
]);
```

## Available Validators

GUMP provides **70+ built-in validators** for comprehensive data validation:

<div id="available_validators">

| Rule                                                                           | Description                                                                                                                                                                                               |
|--------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **required**                                                                   | Ensures the specified key value exists and is not empty (not null, not empty string, not empty array).                                                                                                    |
| **contains**,one;two;use array format if one of the values contains semicolons | Verify that a value is contained within the pre-defined value set.                                                                                                                                        |
| **contains_list**,value1;value2                                                | Verify that a value is contained within the pre-defined value set. Error message will NOT show the list of possible values.                                                                               |
| **doesnt_contain_list**,value1;value2                                          | Verify that a value is contained within the pre-defined value set. Error message will NOT show the list of possible values.                                                                               |
| **boolean**,strict                                                             | Determine if the provided value is a valid boolean. Returns true for: yes/no, on/off, 1/0, true/false. In strict mode (optional) only true/false will be valid which you can combine with boolean filter. |
| **valid_email**                                                                | Determine if the provided email has valid format.                                                                                                                                                         |
| **max_len**,240                                                                | Determine if the provided value length is less or equal to a specific value.                                                                                                                              |
| **min_len**,4                                                                  | Determine if the provided value length is more or equal to a specific value.                                                                                                                              |
| **exact_len**,5                                                                | Determine if the provided value length matches a specific value.                                                                                                                                          |
| **between_len**,3;11                                                           | Determine if the provided value length is between min and max values.                                                                                                                                     |
| **alpha**                                                                      | Determine if the provided value contains only alpha characters.                                                                                                                                           |
| **alpha_numeric**                                                              | Determine if the provided value contains only alpha-numeric characters.                                                                                                                                   |
| **alpha_dash**                                                                 | Determine if the provided value contains only alpha characters with dashed and underscores.                                                                                                               |
| **alpha_numeric_dash**                                                         | Determine if the provided value contains only alpha numeric characters with dashed and underscores.                                                                                                       |
| **alpha_numeric_space**                                                        | Determine if the provided value contains only alpha numeric characters with spaces.                                                                                                                       |
| **alpha_space**                                                                | Determine if the provided value contains only alpha characters with spaces.                                                                                                                               |
| **numeric**                                                                    | Determine if the provided value is a valid number or numeric string.                                                                                                                                      |
| **integer**                                                                    | Determine if the provided value is a valid integer.                                                                                                                                                       |
| **float**                                                                      | Determine if the provided value is a valid float.                                                                                                                                                         |
| **valid_url**                                                                  | Determine if the provided value is a valid URL.                                                                                                                                                           |
| **url_exists**                                                                 | Determine if a URL exists & is accessible.                                                                                                                                                                |
| **valid_ip**                                                                   | Determine if the provided value is a valid IP address.                                                                                                                                                    |
| **valid_ipv4**                                                                 | Determine if the provided value is a valid IPv4 address.                                                                                                                                                  |
| **valid_ipv6**                                                                 | Determine if the provided value is a valid IPv6 address.                                                                                                                                                  |
| **valid_cc**                                                                   | Determine if the input is a valid credit card number.                                                                                                                                                     |
| **valid_name**                                                                 | Determine if the input is a valid human name.                                                                                                                                                             |
| **street_address**                                                             | Determine if the provided input is likely to be a street address using weak detection.                                                                                                                    |
| **iban**                                                                       | Determine if the provided value is a valid IBAN.                                                                                                                                                          |
| **date**,d/m/Y                                                                 | Determine if the provided input is a valid date (ISO 8601) or specify a custom format (optional).                                                                                                         |
| **min_age**,18                                                                 | Determine if the provided input meets age requirement (ISO 8601). Input should be a date (Y-m-d).                                                                                                         |
| **max_numeric**,50                                                             | Determine if the provided numeric value is lower or equal to a specific value.                                                                                                                            |
| **min_numeric**,1                                                              | Determine if the provided numeric value is higher or equal to a specific value.                                                                                                                           |
| **starts**,Z                                                                   | Determine if the provided value starts with param.                                                                                                                                                        |
| **required_file**                                                              | Determine if the file was successfully uploaded.                                                                                                                                                          |
| **extension**,png;jpg;gif                                                      | Check the uploaded file for extension. Doesn't check mime-type yet.                                                                                                                                       |
| **equalsfield**,other_field_name                                               | Determine if the provided field value equals current field value.                                                                                                                                         |
| **guidv4**                                                                     | Determine if the provided field value is a valid GUID (v4)                                                                                                                                                |
| **phone_number**                                                               | Determine if the provided value is a valid phone number.                                                                                                                                                  |
| **regex**,/test-[0-9]{3}/                                                      | Custom regex validator.                                                                                                                                                                                   |
| **valid_json_string**                                                          | Determine if the provided value is a valid JSON string.                                                                                                                                                   |
| **valid_array_size_greater**,1                                                 | Check if an input is an array and if the size is more or equal to a specific value.                                                                                                                       |
| **valid_array_size_lesser**,1                                                  | Check if an input is an array and if the size is less or equal to a specific value.                                                                                                                       |
| **valid_array_size_equal**,1                                                   | Check if an input is an array and if the size is equal to a specific value.                                                                                                                               |

### 🔐 Security Validators

| Rule | Description |
|------|-------------|
| **strong_password** | Validates password with uppercase, lowercase, number and special character (min 8 chars). |
| **jwt_token** | Validates JWT token format (3 base64url parts separated by dots). |
| **hash**,md5 | Validates hash format for specified algorithm (md5, sha1, sha256, sha512). |
| **no_sql_injection** | Detects common SQL injection patterns in input. |
| **no_xss** | Enhanced XSS detection beyond basic sanitize_string. |

### 🌐 Modern Web Validators

| Rule | Description |
|------|-------------|
| **uuid** | Validates UUID format (any version 1-5). |
| **base64** | Validates base64 encoded data. |
| **hex_color** | Validates hexadecimal color code (#FF0000 or #FFF format). |
| **rgb_color** | Validates RGB color format (rgb(255,0,0)). |
| **timezone** | Validates timezone identifier (America/New_York, UTC, etc.). |
| **language_code** | Validates language code (en, en-US format - ISO 639). |
| **country_code** | Validates country code (US, CA format - ISO 3166). |
| **currency_code** | Validates currency code (USD, EUR format - ISO 4217). |

### 📡 Network Validators

| Rule | Description |
|------|-------------|
| **mac_address** | Validates MAC address format (AA:BB:CC:DD:EE:FF or AA-BB-CC-DD-EE-FF). |
| **domain_name** | Validates domain name format (example.com - without protocol). |
| **port_number** | Validates port number (1-65535). |
| **social_handle** | Validates social media handle format (@username or username). |

### 🗺️ Geographic Validators

| Rule | Description |
|------|-------------|
| **latitude** | Validates latitude coordinate (-90 to 90). |
| **longitude** | Validates longitude coordinate (-180 to 180). |
| **postal_code**,US | Validates postal code for specified country (US, CA, UK, DE, FR, AU, JP). |
| **coordinates** | Validates coordinates in lat,lng format (40.7128,-74.0060). |

### 📅 Enhanced Date/Time Validators

| Rule | Description |
|------|-------------|
| **future_date** | Validates that date is in the future. |
| **past_date** | Validates that date is in the past. |
| **business_day** | Validates that date falls on a business day (Monday-Friday). |
| **valid_time** | Validates time format (HH:MM:SS or HH:MM). |
| **date_range**,2024-01-01;2024-12-31 | Validates date falls within specified range. |

### 🔢 Mathematical Validators

| Rule | Description |
|------|-------------|
| **even** | Validates that number is even. |
| **odd** | Validates that number is odd. |
| **prime** | Validates that number is prime. |

### 📝 Content & Format Validators

| Rule | Description |
|------|-------------|
| **word_count**,min,10,max,500 | Validates word count within specified range. |
| **camel_case** | Validates camelCase format (variableName). |
| **snake_case** | Validates snake_case format (variable_name). |
| **url_slug** | Validates URL slug format (my-url-slug). |

</div>

## Comprehensive Validator Reference

### Essential Validators

**`required`** - The most fundamental validator

```php
// Basic usage
'email' => 'required'

// Common combinations
'username' => 'required|alpha_numeric|min_len,3|max_len,20'
'password' => 'required|min_len,8|max_len,100'
```

**`between_len,min;max`** - String length range validation

```php
// Username must be between 3-20 characters
'username' => 'between_len,3;20'

// Description between 10-500 characters
'description' => 'between_len,10;500'

// Works with Unicode characters
'title' => 'between_len,5;100'  // Handles émojis, ñ, etc. correctly
```

### Real-World Usage Examples

**User Registration Form**

```php
$rules = [
    'first_name' => 'required|alpha_space|min_len,2|max_len,50',
    'last_name'  => 'required|alpha_space|min_len,2|max_len,50', 
    'username'   => 'required|alpha_numeric_dash|between_len,3;20',
    'email'      => 'required|valid_email',
    'phone'      => 'phone_number',
    'website'    => 'valid_url',
    'age'        => 'required|integer|min_numeric,13|max_numeric,120',
    'password'   => 'required|min_len,8',
    'password_confirm' => 'required|equalsfield,password',
    'terms'      => 'required|boolean,strict'
];
```

**E-commerce Product Form**

```php
$rules = [
    'name'        => 'required|between_len,3;100',
    'price'       => 'required|float|min_numeric,0.01',
    'category'    => 'required|contains,electronics;clothing;books;home',
    'sku'         => 'required|regex,/^[A-Z]{2}[0-9]{4}$/',
    'description' => 'required|between_len,20;2000',
    'tags'        => 'valid_array_size_greater,0|valid_array_size_lesser,11',
    'active'      => 'boolean',
    'image'       => 'required_file|extension,jpg;png;webp'
];
```

**API Payload Validation**

```php
$rules = [
    'user_id'     => 'required|uuid',
    'email'       => 'required|valid_email', 
    'metadata'    => 'valid_json_string',
    'permissions' => 'valid_array_size_greater,0',
    'expires_at'  => 'date,c',  // ISO 8601 format
    'ip_address'  => 'valid_ip',
    'user_agent'  => 'max_len,500'
];
```

**Security & Authentication Form**

```php
$rules = [
    'password'    => 'required|strong_password',
    'token'       => 'required|jwt_token',
    'api_key'     => 'required|hash,sha256',
    'input'       => 'no_sql_injection|no_xss',
    'timezone'    => 'timezone',
    'language'    => 'language_code'
];
```

**Geographic & Network Validation**

```php
$rules = [
    'latitude'    => 'required|latitude',
    'longitude'   => 'required|longitude',
    'postal_code' => 'required|postal_code,US',
    'coordinates' => 'coordinates',
    'mac_address' => 'mac_address',
    'domain'      => 'domain_name',
    'port'        => 'port_number',
    'twitter'     => 'social_handle'
];
```

**Content & Format Validation**

```php
$rules = [
    'variable_name' => 'required|snake_case',
    'functionName'  => 'required|camel_case',
    'blog_slug'     => 'required|url_slug',
    'article_body'  => 'required|word_count,min,100,max,2000',
    'color_theme'   => 'hex_color',
    'schedule_date' => 'required|future_date|business_day',
    'prime_number'  => 'prime'
];
```

### Advanced Validation Patterns

**Conditional Validation**

```php
// Only validate credit card if payment method is 'credit_card'
if ($input['payment_method'] === 'credit_card') {
    $rules['credit_card'] = 'required|valid_cc';
    $rules['expiry_date'] = 'required|date,m/Y';
}
```

**File Upload with Metadata**

```php
$rules = [
    'title'       => 'required|between_len,3;100',
    'document'    => 'required_file|extension,pdf;doc;docx',
    'category'    => 'required|contains_list,public;private;draft',
    'tags'        => 'valid_array_size_lesser,10',
    'description' => 'max_len,1000'
];
```

**Nested Array Validation**

```php
$rules = [
    'company.name'           => 'required|between_len,2;100',
    'company.email'          => 'required|valid_email',
    'employees.*.name'       => 'required|valid_name',
    'employees.*.email'      => 'required|valid_email', 
    'employees.*.age'        => 'required|integer|min_numeric,18',
    'departments.*.budget'   => 'required|float|min_numeric,0'
];
```

> **💡 Pro Tips**:
>
> **Parameter Conflicts**: When using pipe (`|`) or semicolon (`;`) in validator parameters, use array format:
> ```php
> // ❌ Wrong - will break parsing
> 'field' => 'regex,/part|of;pattern/'
> 
> // ✅ Correct - use array format
> 'field' => ['regex' => '/part|of;pattern/']
> ```
>
> **Performance**: Put faster validators first in chains:
> ```php
> // ✅ Good - required fails fast for empty values
> 'email' => 'required|valid_email|max_len,255'
> 
> // ❌ Less efficient - validates email format on empty values
> 'email' => 'valid_email|required|max_len,255'
> ```
>
> **Boolean Values**: The `boolean` filter accepts various formats:
> ```php
> // All these become TRUE: '1', 1, 'true', true, 'yes', 'on'
> // All these become FALSE: '0', 0, 'false', false, 'no', 'off', null, ''
> ```

## Available Filters

GUMP includes 15+ filters for data sanitization and transformation:

<div id="available_filters">

| Filter                 | Description                                                                                                           |
|------------------------|-----------------------------------------------------------------------------------------------------------------------|
| **noise_words**        | Replace noise words in a string (http://tax.cchgroup.com/help/Avoiding_noise_words_in_your_search.htm).               |
| **rmpunctuation**      | Remove all known punctuation from a string.                                                                           |
| **urlencode**          | Sanitize the string by urlencoding characters.                                                                        |
| **htmlencode**         | Sanitize the string by converting HTML characters to their HTML entities.                                             |
| **sanitize_email**     | Sanitize the string by removing illegal characters from emails.                                                       |
| **sanitize_numbers**   | Sanitize the string by removing illegal characters from numbers.                                                      |
| **sanitize_floats**    | Sanitize the string by removing illegal characters from float numbers.                                                |
| **sanitize_string**    | Sanitize the string by removing any script tags.                                                                      |
| **boolean**            | Converts ['1', 1, 'true', true, 'yes', 'on'] to true, anything else is false ('on' is useful for form checkboxes).    |
| **basic_tags**         | Filter out all HTML tags except the defined basic tags.                                                               |
| **whole_number**       | Convert the provided numeric value to a whole number.                                                                 |
| **ms_word_characters** | Convert MS Word special characters to web safe characters. ([“ ”] => ", [‘ ’] => ', [–] => -, […] => ...) |
| **lower_case**         | Converts to lowercase.                                                                                                |
| **upper_case**         | Converts to uppercase.                                                                                                |
| **slug**               | Converts value to url-web-slugs.                                                                                      |
| **trim**               | Remove spaces from the beginning and end of strings.                                                                  |
</div>

### Filter Chaining Example

```php
$filtered = GUMP::filter_input([
    'title'       => '  My Amazing Blog Post!!!  ',
    'description' => '<script>alert("xss")</script>This is a description with <b>bold</b> text.',
    'price'       => '$19.99 USD',
    'active'      => 'yes'
], [
    'title'       => 'trim|ms_word_characters|slug',
    'description' => 'trim|sanitize_string',
    'price'       => 'sanitize_floats',
    'active'      => 'boolean'
]);

// Results:
// $filtered['title'] = 'my-amazing-blog-post'
// $filtered['description'] = 'This is a description with bold text.'
// $filtered['price'] = '19.99'
// $filtered['active'] = true
```

## Advanced Usage

### Instance Methods

```php
$gump = new GUMP('en'); // Set language

// Validate data without filtering
$validation_result = $gump->validate($_POST, [
    'email' => 'required|valid_email'
]);

// Filter data without validation
$filtered_data = $gump->filter($_POST, [
    'content' => 'trim|sanitize_string'
]);

// Sanitize data (UTF-8 conversion)
$sanitized = $gump->sanitize($_POST, ['allowed_field1', 'allowed_field2']);

// Get detailed error information
if ($gump->errors()) {
    $readable_errors = $gump->get_readable_errors(); // HTML formatted
    $simple_errors = $gump->get_errors_array();      // Field => message array
}
```

### Field Name Customization

```php
// Set friendly field names for error messages
GUMP::set_field_name('usr_nm', 'Username');
GUMP::set_field_names([
    'usr_nm' => 'Username',
    'pwd'    => 'Password',
    'em'     => 'Email Address'
]);

// Now validation errors will show friendly names
$is_valid = GUMP::is_valid(['usr_nm' => ''], ['usr_nm' => 'required']);
// Error: "Username is required" (instead of "Usr nm is required")
```

### Global Error Message Customization

```php
// Set custom error messages for validators
GUMP::set_error_message('required', 'The {field} field cannot be empty');
GUMP::set_error_message('valid_email', 'Please provide a valid email for {field}');

// Set multiple custom messages
GUMP::set_error_messages([
    'required'      => 'Please fill out the {field} field',
    'min_len'       => '{field} must be at least {param} characters long',
    'valid_email'   => 'The email address in {field} is not valid'
]);
```

## Internationalization

GUMP supports 19 languages out of the box:

**Supported Languages**: German (de), Greek (el), English (en), Esperanto (eo), Spanish (es), Persian (fa), French (fr),
Hebrew (he), Hungarian (hu), Indonesian (id), Italian (it), Japanese (ja), Dutch (nl), Portuguese Brazil (pt-br),
Russian (ru), Turkish (tr), Ukrainian (uk), Vietnamese (vi), Chinese Simplified (zh-CN)

```php
// Set language during instantiation
$gump = new GUMP('es'); // Spanish
$gump = new GUMP('fr'); // French
$gump = new GUMP('de'); // German

// Validation errors will now be in the selected language
$result = $gump->validate(['email' => 'invalid'], ['email' => 'valid_email']);
```

## Custom Validators & Filters

### Adding Custom Validators

```php
// Add a custom validator with callback
GUMP::add_validator('strong_password', function($field, array $input, array $params, $value) {
    // Must contain at least 1 uppercase, 1 lowercase, 1 number, and 1 special char
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/', $value);
}, 'The {field} must be a strong password with uppercase, lowercase, number and special character.');

// Usage
$is_valid = GUMP::is_valid(['password' => 'weak'], ['password' => 'strong_password']);

// Check if validator exists
if (GUMP::has_validator('strong_password')) {
    echo "Custom validator is available!";
}
```

### Adding Custom Filters

```php
// Add a custom filter
GUMP::add_filter('mask_email', function($value, array $params = []) {
    $parts = explode('@', $value);
    if (count($parts) === 2) {
        $username = substr($parts[0], 0, 2) . str_repeat('*', strlen($parts[0]) - 2);
        return $username . '@' . $parts[1];
    }
    return $value;
});

// Usage
$filtered = GUMP::filter_input(['email' => 'john@example.com'], ['email' => 'mask_email']);
// Result: 'jo***@example.com'

// Check if filter exists
if (GUMP::has_filter('mask_email')) {
    echo "Custom filter is available!";
}
```

### Extending GUMP Class

```php
class CustomGUMP extends GUMP
{
    // Custom validator method (prefix with 'validate_')
    protected function validate_is_even($field, array $input, array $params = [], $value = null)
    {
        return is_numeric($value) && ($value % 2 == 0);
    }
    
    // Custom filter method (prefix with 'filter_')
    protected function filter_add_prefix($value, array $params = [])
    {
        $prefix = isset($params[0]) ? $params[0] : 'PREFIX_';
        return $prefix . $value;
    }
}

$custom_gump = new CustomGUMP();

// Use custom validator
$result = $custom_gump->validate(['number' => 5], ['number' => 'is_even']);

// Use custom filter  
$filtered = $custom_gump->filter(['name' => 'John'], ['name' => 'add_prefix,MR_']);
// Result: 'MR_John'
```

## Configuration

### Global Delimiter Configuration

Customize the delimiters used in validation rule strings:

```php
// Default configuration
GUMP::$rules_delimiter = '|';                    // Separates rules: 'required|email'
GUMP::$rules_parameters_delimiter = ',';         // Separates parameters: 'min_len,6'
GUMP::$rules_parameters_arrays_delimiter = ';'; // Separates array items: 'contains,a;b;c'

// Custom configuration example
GUMP::$rules_delimiter = '&';                    // 'required&email'
GUMP::$rules_parameters_delimiter = ':';         // 'min_len:6'
GUMP::$rules_parameters_arrays_delimiter = '|'; // 'contains:a|b|c'
```

### Field Character Replacement

```php
// Characters that will be replaced with spaces in field names for error messages
GUMP::$field_chars_to_spaces = ['_', '-', '.'];

// 'user_name' becomes 'User Name' in error messages
// 'first-name' becomes 'First Name' in error messages
```

## Testing

GUMP includes comprehensive test coverage with PHPUnit:

```bash
# Install development dependencies
composer install --dev

# Run all tests
composer test

# Run tests with coverage
./vendor/bin/phpunit --coverage-html coverage

# Check documentation consistency
composer check

# Dump documentation (for contributors)
composer dump
```

### Running Tests in Docker

```bash
# Build and run tests in Docker
cd dev/
./build.sh
./run_tests_docker.sh
```

## Contributing

We welcome contributions! Please read our [Contributing Guidelines](CONTRIBUTING.md) before submitting PRs.

### Development Setup

1. Fork the repository
2. Clone your fork: `git clone https://github.com/yourusername/gump.git`
3. Install dependencies: `composer install`
4. Create a feature branch: `git checkout -b feature/amazing-feature`
5. Make your changes and add tests
6. Run tests: `composer test`
7. Submit a pull request

### Contribution Guidelines

- **Add tests** for new features and bug fixes
- **Follow PSR-12** coding standards
- **Update documentation** for new validators/filters
- **Add translations** for new error messages
- **Maintain backward compatibility**

### Areas We Need Help With

- 🌍 **Translations** - Help us support more languages
- 🧪 **Test Coverage** - Add more edge case tests
- 📚 **Documentation** - Improve examples and guides
- 🚀 **Performance** - Optimize validation algorithms
- 🛡️ **Security** - Security audits and improvements

## Security

### Security Best Practices

- Always validate AND filter user input
- Use appropriate validators for your data types
- Be cautious with `regex` validator - avoid ReDoS attacks
- Use `sanitize_string` filter to prevent XSS
- Validate file uploads thoroughly
- Keep GUMP updated to the latest version

### Security Features

- **XSS Protection**: Built-in `sanitize_string` filter
- **SQL Injection Prevention**: Proper data validation
- **File Upload Security**: Extension and type validation
- **Input Sanitization**: Multiple sanitization filters
- **Safe Defaults**: Secure by default configuration

## Support

### Community Support

- 🐛 **Bug Reports**: [GitHub Issues](https://github.com/wixel/gump/issues)
- 💡 **Feature Requests**: [GitHub Discussions](https://github.com/wixel/gump/discussions)
- 📚 **Documentation**: [GitHub Wiki](https://github.com/wixel/gump/wiki)
- 💬 **Community Chat**: [Discord Server](https://discord.gg/wixel)

## Statistics

- ⭐ **GitHub Stars**: 1000+
- 📦 **Downloads**: 1M+ via Packagist
- 🏭 **Production Use**: Thousands of projects
- 🌍 **Languages**: 19 supported languages
- ⚡ **Performance**: <1ms validation time for typical forms
- 🧪 **Test Coverage**: 100%

## Why Choose GUMP?

### ✅ Battle-Tested

- **10+ years** in production
- **Trusted** by thousands of developers
- **Proven** in high-traffic applications

### ⚡ Performance First

- **Zero dependencies** - no bloat
- **Optimized algorithms** - fast validation
- **Memory efficient** - low resource usage

### 🔒 Security Focused

- **XSS protection** built-in
- **Regular security audits**
- **Secure defaults** everywhere

### 🌍 Global Ready

- **19 languages** supported
- **UTF-8 compatible**
- **Timezone aware** date validation

### 🛠️ Developer Friendly

- **Clean, simple API**
- **Excellent documentation**
- **Extensive examples**
- **Framework agnostic**

## License

GUMP is open-source software licensed under the [MIT License](LICENSE).

```
MIT License

Copyright (c) 2013-2025 Sean Nieuwoudt.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```
