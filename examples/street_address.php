#!/usr/bin/php -q
<?php

error_reporting(-1);

ini_set('display_errors', 1);

require "gump.class.php";

$data = array(
	'street' => '6 Avondans Road'
);

$validated = GUMP::is_valid($data, array(
	'street' => 'required|street_address'
));

if($validated === true) {
	echo "Valid Street Address\n";
} else {
	print_r($validated);
}