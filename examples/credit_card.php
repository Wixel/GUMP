#!/usr/bin/php -q
<?php

require "../gump.class.php";

$_POST = array(
	'cc' => '987230234-2498234-24234-23' // This is not a valid credit card number
)

$rules = array(
	'cc' => 'valid_cc'
);

print_r(GUMP::validate($_POST, $rules));
