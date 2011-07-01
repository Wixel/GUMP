#!/usr/bin/php -q
<?php

require "../gump.class.php";

$_POST = array(
	'url' => 'http://ahakjdhkahddfsdfsdfdkjad.com' // This url obviously does not exist
);

$rules = array(
	'url' => 'url_exists'
);

print_r(GUMP::validate($_POST, $rules));
