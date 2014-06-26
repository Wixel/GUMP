#!/usr/bin/php -q
<?php

error_reporting(-1);

ini_set('display_errors', 1);

require "../gump.class.php";

$data = array(
	'str' => null
);

$rules = array(
	'str' => 'required'
);

GUMP::set_field_name("str", "Street");

$validated = GUMP::is_valid($data, $rules);

if($validated === true) {
	echo "Valid Street Address\n";
} else {
	print_r($validated);
}