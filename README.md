# Getting started

GUMP is a light-weight, dependency-free validation class for PHP 7+. The aim is to make data validation and filtering as painless as possible while being easily extendible to fit any project with or without a framework.

> v2.0 is a complete rewrite of the original and is therefore not backwards compatible.

###### Install with composer

Add the following to your `composer.json` file:

```json
{
    "require": {
        "wixel/gump": "dev-master"
    }
}
```

To install the older version on GUMP, please use: 

```json
{
    "require": {
        "wixel/gump": "1.5.6"
    }
}
```

### Quick Start Overview

```php
require 'gump.class.php';

# Load any language files
GUMP::load_lang('lang/en.yaml');

# Manually create a validator or filter function
GUMP::register('required', function($value, $params = null) { 
	$value = trim($value);

	if(!empty($value)) {
		return $value;
	} else {
		throw new Exception("Value is required");
	}
});


# Get or set config value (accesible inside GUMP functions)
GUMP::config('key', 'value');

# Call an individual GUMP method
$value = GUMP::call('method', $value, $params);

# Override a field with a custom field name
GUMP::set_field_name('key', 'Field Name');

$input = array(
	'name' => 'A Name',
	'email' => 'test@test.com'
);

$result = GUMP::run($input, array(
	'name' => 'required',
	'email' => 'valid_email'
));

if($result->valid()) {
	$result->fields();
} else {
	$result->errors('en');
}
```

## TODO

- Donations button
- Virtual Machine
- Available languages
- Contribution guide & quality control
- PHP Unit Tests
- Readme
	- Quick start overview
	- Installing
	- Creating validators
	- Creating filters
	- Using results
	- Calling singular methods
	- Adding languages
	- Available validators
	- Available filters
	- Setting configs 
	- Setting custom field names
	- Running Examples (+ VM)
	- Running Tests
	- Add: strong password validator