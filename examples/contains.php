#!/usr/bin/php -q
<?php

require "../gump.class.php";

$validator = new GUMP();

$rules = array(
  'account_type' => "required|contains,pro free basic premium",
  'priority'     => "required|contains,'low' 'medium' 'very high'",
);

echo "\nVALID DATA TEST:\n\n";

// Valid Data
$_POST_VALID = array(
  'account_type' => 'pro',
  'priority'     => 'very high'
);

$valid = $validator->validate(
  $_POST_VALID, $rules
);

if($valid !== true) {
  echo $validator->get_readable_errors(true);
} else {
  echo "Validation passed! \n";
}

echo "\nINVALID DATA TEST:\n\n";

// Invalid
$_POST_INVALID = array(
  'account_type' => 'bad',
  'priority'     => 'unknown'
);

$invalid = $validator->validate(
  $_POST_INVALID, $rules
);

if($invalid !== true) {
  echo $validator->get_readable_errors(true);
  echo "\n\n";
} else {
  echo "Validation passed!\n\n";
}