# Getting started

GUMP is a standalone PHP data validation and filtering class that makes validating any data easy and painless without the reliance on a framework.

[![Build Status](https://travis-ci.org/Wixel/GUMP.svg?branch=master)](https://travis-ci.org/Wixel/GUMP)
[![Coverage Status](https://coveralls.io/repos/github/Wixel/GUMP/badge.svg?branch=master)](https://coveralls.io/github/Wixel/GUMP?branch=master)

#### There are 2 ways to install GUMP

##### Install with composer (prefered, modern way)

```
composer require wixel/gump
```

##### Install manually

1. Download GUMP
2. Unzip it and copy the directory into your PHP project directory.

Include it in your project:

```php
require 'gump.class.php';
require 'src/Helpers.php';

$is_valid = GUMP::is_valid($_POST, [
    'username' => 'required|alpha_numeric',
    'password' => 'required|max_len,100|min_len,6'
]);

if ($is_valid === true) {
    // continue
} else {
    print_r($is_valid);
}
```


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

# Example (Long format)

The following example is part of a registration form, the flow should be pretty standard

```php
# Note that filters and validators are separate rule sets and method calls. There is a good reason for this.

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

# Example (Short format)

The short format is an alternative way to run the validation.

```php
$data = [
    'street' => '6 Avondans Road'
];

$validated = GUMP::is_valid($data, [
    'street' => 'required|street_address'
]);

if ($validated === true) {
    echo "Valid Street Address!";
} else {
    print_r($validated);
}
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


Available Validators
--------------------
<details><summary>Show all validators</summary><div><br>

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
| **valid_twitter**                             | Determine if the provided value is a valid twitter handle.                                                                  |
</div></details>

Available Filters
-----------------
Filters can be any PHP function that returns a string. You don't need to create your own if a PHP function exists that does what you want the filter to do.

* sanitize_string `Remove script tags and encode HTML entities, similar to GUMP::xss_clean();`
* urlencode `Encode url entities`
* htmlencode `Encode HTML entities`
* sanitize_email `Remove illegal characters from email addresses`
* sanitize_numbers `Remove any non-numeric characters`
* sanitize_floats `Remove any non-float characters`
* trim `Remove spaces from the beginning and end of strings`
* base64_encode `Base64 encode the input`
* base64_decode `Base64 decode the input`
* sha1 `Encrypt the input with the secure sha1 algorithm`
* md5 `MD5 encode the input`
* noise_words `Remove noise words from string`
* json_encode `Create a json representation of the input`
* json_decode `Decode a json string`
* rmpunctuation `Remove all known punctuation characters from a string`
* basic_tags `Remove all layout orientated HTML tags from text. Leaving only basic tags`
* whole_number `Ensure that the provided numeric value is represented as a whole number`
* ms_word_characters `Converts MS Word special characters [“”‘’–…] to web safe characters`
* lower_case `Converts to lowercase`
* upper_case `Converts to uppercase`
* slug `Creates web safe url slug`

#  Creating your own validators and filters

Adding custom validators and filters is made easy by using callback functions.

```php
/*
   Create a custom validation rule named "is_object".
   The callback receives 3 arguments:
   The field to validate, the values being validated, and any parameters used in the validation rule.
   It should return a boolean value indicating whether the value is valid.
*/
GUMP::add_validator("is_object", function($field, $input, $param = NULL) {
    return is_object($input[$field]);
}, 'Your custom error message here');

/*
   Create a custom filter named "upper".
   The callback function receives two arguments:
   The value to filter, and any parameters used in the filter rule. It should returned the filtered value.
*/
GUMP::add_filter("upper", function($value, $params = NULL) {
    return strtoupper($value);
});
```

Alternately, you can simply create your own class that extends the GUMP class.

```php
class MyClass extends GUMP
{
    public function filter_myfilter($value, $param = NULL)
    {
        return strtoupper($value);
    }

    public function validate_myvalidator($field, $input, $param = NULL)
    {
        // validator
    }
}

$validator = new MyClass();
$validated = $validator->validate($_POST, $rules);
```

Please see `examples/custom_validator.php` for further information.

Remember to create a public methods with the correct parameter types and parameter counts.

* For filter methods, prepend the method name with "filter_".
* For validator methods, prepend the method name with "validate_".

# Set Custom Field Names

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

# validating file fields

```php
$is_valid = GUMP::is_valid(array_merge($_POST,$_FILES), [
    'title' => 'required|alpha_numeric',
    'image' => 'required_file|extension,png;jpg'
]);

if ($is_valid === true) {
    // continue
} else {
    print_r($is_valid);
}
```

Running the examples:
------------------

1. Open up your terminal
2. cd [GUMP DIRECTORY/examples]
3. php [file].php

The output will depend on the input data.

# TODO
* A currency validator
* A country validator
* Location co-ordinates validator
* HTML validator (HTMLPurifier or similar)
* Language validation ... determine if a piece of text is a specified language
* Validate a spam domain or IP.
* Validate a spam email address
* Validate spam text with askimet or something similar
* W3C validation filter?
* A filter that integrates with an HTML tidy service?: http://infohound.net/tidy/
* Add a twitter & facebook profile url validator: http://stackoverflow.com/questions/2845243/check-if-twitter-username-exists
* Add more logical examples - log in form, profile update form, blog post form, etc etc.
* Add validators to allow checking the PHP $_FILES array.
* Allow a validator that can check for existing files on the host machine
* Add an 'is empty' validator check
* A secure password validator
* alpha_dash_number validator


# Contributors
https://github.com/Wixel/GUMP/graphs/contributors
