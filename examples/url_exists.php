#!/usr/bin/php -q
<?php

require "../gump.class.php";

$validator = new GUMP();

$_POST = array(
	'url' => 'http://ahakjdhkahddfsdfsdfdkjad.com' // This url obviously does not exist
);

$rules = array(
	'url' => 'url_exists'
);

print_r($validator->validate($_POST, $rules));