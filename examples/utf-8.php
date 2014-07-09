#!/usr/bin/php -q
<?php

error_reporting(-1);

ini_set('display_errors', 1);

require "../gump.class.php";

$data = array(
	'one' => 'Freiheit, Mobilität und Unabhängigkeit lebt. ö, Ä, é, or ß',
	'two' => 'ß'
);

$validated = GUMP::is_valid($data, array(
	'one' => 'required|min_len,10',
	'two' => 'required|min_len,1',
));

if($validated === true) {
	echo "Valid Text\n";
} else {
	print_r($validated);
}