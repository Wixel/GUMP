#!/usr/bin/php -q
<?php

require "../gump.class.php";

// Add the custom validator
GUMP::add_validator("is_object", function($field, $input, $param = NULL) {
    return is_object($input[$field]);
});

// Generic test data
$input_data = array(
  'not_object'   => 5,
  'valid_object' => new stdClass()
);

$rules = array(
  'not_object'   => "required|is_object",
  'valid_object' => "required|is_object"
);

// METHOD 1 (Long):

$validator = new GUMP();

$validated = $validator->validate(
	$input_data, $rules
);

if($validated === true) {
	echo "Validation passed!";
} else {
	echo $validator->get_readable_errors(true);
}

// METHOD 2 (Short):

$is_valid = GUMP::is_valid($input_data, $rules);

if($is_valid === true) {
	echo "Validation passed!";
} else {
    print_r($is_valid);
}
