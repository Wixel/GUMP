<?php
require "gump.class.php";

$input = array(
	'name'     => 'Sean Nieuwoudt',
	'email'    => 'sean@isean.co.za',
	'password' => 'testtest'
);

GUMP::register('required', function($value, $params = null) { 
	$value = trim($value);

	if(!empty($value)) {
		return $value;
	} else {
		throw new Exception();
	}
});

$result = GUMP::run($input, array('name' => 'required'));

print_r($result);