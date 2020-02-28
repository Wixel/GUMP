# Getting started

GUMP is a standalone PHP data validation and filtering class that makes validating any data easy and painless without the reliance on a framework.

[![License: MIT](https://img.shields.io/badge/License-MIT-red.svg)](https://opensource.org/licenses/MIT)
[![Build Status](https://travis-ci.org/Wixel/GUMP.svg?branch=master)](https://travis-ci.org/Wixel/GUMP)
[![Coverage Status](https://coveralls.io/repos/github/Wixel/GUMP/badge.svg?branch=master)](https://coveralls.io/github/Wixel/GUMP?branch=master)

#### Install with composer

```
composer require wixel/gump
```

### Short format example

```php

$is_valid = GUMP::is_valid(array_merge($_POST, $_FILES), [
    'username' => 'required|alpha_numeric',
    'password' => 'required|max_len,100|min_len,6',
    'avatar'   => 'required_file|extension,png;jpg'
]);

if ($is_valid === true) {
    // continue
} else {
    print_r($is_valid);
}
```

### Long format example

```php
$gump = new GUMP();

$_POST = $gump->sanitize($_POST); // You don't have to sanitize, but it's safest to do so.

$gump->validation_rules([
    'username'    => 'required|alpha_numeric|max_len,100|min_len,6',
    'password'    => 'required|max_len,100|min_len,6',
    'email'       => 'required|valid_email',
    'gender'      => 'required|exact_len,1|contains,m f',
    'credit_card' => 'required|valid_cc'
]);

$gump->filter_rules([
    'username' => 'trim|sanitize_string',
    'password' => 'trim',
    'email'    => 'trim|sanitize_email',
    'gender'   => 'trim',
    'bio'      => 'noise_words'
]);

$validated_data = $gump->run($_POST);

if ($validated_data === false) {
    echo $gump->get_readable_errors(true);
} else {
    print_r($validated_data); // validation successful
}
```



:star: Available Validators
---------------------------
<div id="available_validators">

| Rule                                          | Description                                                                                                                 |
|-----------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------|
| **required**                                  | Ensures the specified key value exists and is not empty.                                                                    |
| **contains**,'value1' 'space separated value' | Verify that a value is contained within the pre-defined value set.                                                          |
| **contains_list**,value1;value2               | Verify that a value is contained within the pre-defined value set. Error message will NOT show the list of possible values. |
| **doesnt_contain_list**,value;value;value     | Verify that a value is contained within the pre-defined value set. Error message will NOT show the list of possible values. |
| **valid_email**                               | Determine if the provided email has valid format.                                                                           |
| **max_len**,240                               | Determine if the provided value length is less or equal to a specific value.                                                |
| **min_len**,4                                 | Determine if the provided value length is more or equal to a specific value.                                                |
| **exact_len**,5                               | Determine if the provided value length matches a specific value.                                                            |
| **alpha**                                     | Determine if the provided value contains only alpha characters.                                                             |
| **alpha_numeric**                             | Determine if the provided value contains only alpha-numeric characters.                                                     |
| **alpha_dash**                                | Determine if the provided value contains only alpha characters with dashed and underscores.                                 |
| **alpha_numeric_dash**                        | Determine if the provided value contains only alpha numeric characters with dashed and underscores.                         |
| **alpha_numeric_space**                       | Determine if the provided value contains only alpha numeric characters with spaces.                                         |
| **alpha_space**                               | Determine if the provided value contains only alpha characters with spaces.                                                 |
| **numeric**                                   | Determine if the provided value is a valid number or numeric string.                                                        |
| **integer**                                   | Determine if the provided value is a valid integer.                                                                         |
| **boolean**                                   | Determine if the provided value is a PHP accepted boolean. Also returns true for strings: yes/no, on/off, 1/0, true/false.  |
| **float**                                     | Determine if the provided value is a valid float.                                                                           |
| **valid_url**                                 | Determine if the provided value is a valid URL.                                                                             |
| **url_exists**                                | Determine if a URL exists & is accessible.                                                                                  |
| **valid_ip**                                  | Determine if the provided value is a valid IP address.                                                                      |
| **valid_ipv4**                                | Determine if the provided value is a valid IPv4 address.                                                                    |
| **valid_ipv6**                                | Determine if the provided value is a valid IPv6 address.                                                                    |
| **valid_cc**                                  | Determine if the input is a valid credit card number.                                                                       |
| **valid_name**                                | Determine if the input is a valid human name.                                                                               |
| **street_address**                            | Determine if the provided input is likely to be a street address using weak detection.                                      |
| **iban**                                      | Determine if the provided value is a valid IBAN.                                                                            |
| **date**,d/m/Y                                | Determine if the provided input is a valid date (ISO 8601) or specify a custom format (optional).                           |
| **min_age**,18                                | Determine if the provided input meets age requirement (ISO 8601).                                                           |
| **max_numeric**,50                            | Determine if the provided numeric value is lower or equal to a specific value.                                              |
| **min_numeric**,1                             | Determine if the provided numeric value is higher or equal to a specific value.                                             |
| **starts**,Z                                  | Determine if the provided value starts with param.                                                                          |
| **required_file**                             | Determine if the file was successfully uploaded.                                                                            |
| **extension**,png;jpg;gif                     | Check the uploaded file for extension. Doesn't check mime-type yet.                                                         |
| **equalsfield**,other_field_name              | Determine if the provided field value equals current field value.                                                           |
| **guidv4**                                    | Determine if the provided field value is a valid GUID (v4)                                                                  |
| **phone_number**                              | Determine if the provided value is a valid phone number.                                                                    |
| **regex**,/test-[0-9]{3}/                     | Custom regex validator.                                                                                                     |
| **valid_json_string**                         | Determine if the provided value is a valid JSON string.                                                                     |
| **valid_array_size_greater**,1                | Check if an input is an array and if the size is more or equal to a specific value.                                         |
| **valid_array_size_lesser**,1                 | Check if an input is an array and if the size is less or equal to a specific value.                                         |
| **valid_array_size_equal**,1                  | Check if an input is an array and if the size is equal to a specific value.                                                 |
| **valid_twitter**                             | Determine if the provided value is a valid Twitter account.                                                                 |
</div>

:star: Available Filters
------------------------
Filters can be any PHP function that returns a string. You don't need to create your own if a PHP function exists that does what you want the filter to do.

<div id="available_filters">

| Filter                 | Description                                                                                                         |
|------------------------|---------------------------------------------------------------------------------------------------------------------|
| **noise_words**        | Replace noise words in a string (http://tax.cchgroup.com/help/Avoiding_noise_words_in_your_search.htm).             |
| **rmpunctuation**      | Remove all known punctuation from a string.                                                                         |
| **sanitize_string**    | Sanitize the string by removing any script tags.                                                                    |
| **urlencode**          | Sanitize the string by urlencoding characters.                                                                      |
| **htmlencode**         | Sanitize the string by converting HTML characters to their HTML entities.                                           |
| **sanitize_email**     | Sanitize the string by removing illegal characters from emails.                                                     |
| **sanitize_numbers**   | Sanitize the string by removing illegal characters from numbers.                                                    |
| **sanitize_floats**    | Sanitize the string by removing illegal characters from float numbers.                                              |
| **basic_tags**         | Filter out all HTML tags except the defined basic tags.                                                             |
| **whole_number**       | Convert the provided numeric value to a whole number.                                                               |
| **ms_word_characters** | Convert MS Word special characters to web safe characters. ([“, ”, ‘, ’, –, …] => [", ", ', ', -, ...]) |
| **lower_case**         | Converts to lowercase.                                                                                              |
| **upper_case**         | Converts to uppercase.                                                                                              |
| **slug**               | Converts value to url-web-slugs.                                                                                    |
| **trim**               | Remove spaces from the beginning and end of strings. (PHP)                                                          |
| **base64_encode**      | Base64 encode the input. (PHP)                                                                                      |
| **base64_decode**      | Base64 decode the input. (PHP)                                                                                      |
</div>

#### Available Methods

```php
// Shorthand validation
is_valid(array $data, array $rules)

// Get or set the validation rules
validation_rules(array $rules);

// Get or set the filtering rules
filter_rules(array $rules);

// Runs the filter and validation routines
run(array $data);

// Strips and encodes unwanted characters
xss_clean(array $data);

// Sanitizes data and converts strings to UTF-8 (if available),
// optionally according to the provided field whitelist
sanitize(array $input, $whitelist = NULL);

// Validates input data according to the provided ruleset (see example)
validate(array $input, array $ruleset);

// Filters input data according to the provided filterset (see example)
filter(array $input, array $filterset);

// Returns human readable error text in an array or string
get_readable_errors($convert_to_string = false);

// Fetch an array of validation errors indexed by the field names
get_errors_array();

// Override field names with readable ones for errors
set_field_name($field, $readable_name);
```


Match data-keys against rules-keys
-------------
We can check if there is a rule specified for every data-key, by adding an extra parameter to the run method.

```
$gump->run($_POST, true);
```

If it doesn't match the output will be:
```
There is no validation rule for <span class=\"$field_class\">$field</span>
```

Return Values
-------------
`run()` returns one of two types:

*ARRAY* containing the successfully validated and filtered data when the validation is successful

*BOOLEAN* False when the validation has failed

`validate()` returns one of two types:

*ARRAY* containing key names and validator names when data does not pass the validation.

You can use this array along with your language helpers to determine what error message to show.

*BOOLEAN* value of TRUE if the validation was successful.

`filter()` returns the exact array structure that was parsed as the `$input` parameter, the only difference would be the filtered data.

###  Creating your own validators and filters

Adding custom validators and filters is made easy by using callback functions.

```php
/**
 * You would call it like 'equals_string,someString'
 *
 * @param string  $field Name of the field
 * @param array   $input Access to the whole input data
 * @param string  $param Rule parameters (optional)
 *
 * @return bool   true or false whether the validation was successful or not
 */
GUMP::add_validator("equals_string", function($field, $input, $param = null) {
    return $input[$field] === $param;
}, 'Field {field} does not equal {param}.');


/**
 * @param string  $value Value
 * @param string  $param Filter parameters (optional)
 *
 * @return mixed  result of filtered value
 */
GUMP::add_filter("upper", function($value, $params = null) {
    return strtoupper($value);
});
```

Alternately, you can simply create your own class that extends the GUMP class.

```php
class MyClass extends GUMP
{
    /**
     * @param string  $value Value
     * @param string  $param Filter parameters (optional)
     *
     * @return mixed  result of filtered value
     */
    public function filter_myfilter($value, $param = null)
    {
        return strtoupper($value);
    }

    /**
     * @param string  $field Name of the field
     * @param array   $input Access to the whole input data
     * @param string  $param Rule parameters (optional)
     *
     * @return bool   true or false whether the validation was successful or not
     */
    public function validate_myvalidator($field, $input, $param = null)
    {
        return $input[$field] === 'good_value';
    }
}

$validator = new MyClass();
$validated = $validator->validate($_POST, $rules);
```

Remember to create a public methods with the correct parameter types and parameter counts.

* For filter methods, prepend the method name with "filter_".
* For validator methods, prepend the method name with "validate_".

### Set Custom Field Names

You can easily override your form field names for improved readability in errors using the `GUMP::set_field_name($field, $readable_name)` method as follows:

```php
$data = [
    'str' => null
];

$rules = [
    'str' => 'required'
];

GUMP::set_field_name("str", "Street");

$validated = GUMP::is_valid($data, $rules);

if ($validated === true) {
    echo "Valid Street Address\n";
} else {
    print_r($validated);
}
```
