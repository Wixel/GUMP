#!/usr/bin/php -q
<?php

require "gump.php";

$validator = new GUMP();

$rules = array(
	'missing'   	=> 'required',
	'email'     	=> 'valid_email',
	'max_len'   	=> 'max_len,1',
	'min_len'   	=> 'min_len,4',
	'exact_len' 	=> 'exact_len,10',
	'alpha'	       	=> 'alpha',
	'alpha_numeric' => 'alpha_numeric',
	'alpha_dash'	=> 'alpha_dash',
	'numeric'		=> 'numeric',
	'integer'		=> 'integer',
	'boolean'		=> 'boolean',
	'float'			=> 'float',
	'valid_url'		=> 'valid_url',
	'valid_ip'		=> 'valid_ip'
);

$invalid_data = array(
	'missing'   	=> '',
	'email'     	=> 'not a valid email',
	'max_len'   	=> '1234567890',
	'min_len'   	=> '1',
	'exact_len' 	=> '123456',
	'alpha'	       	=> '*(^*^*&',
	'alpha_numeric' => 'abcdefg12345+',
	'alpha_dash'	=> 'abcdefg12345-_+',
	'numeric'		=> 'one, two',
	'integer'		=> '1,003',
	'boolean'		=> 'this is not a boolean',
	'float'			=> 'not a float',
	'valid_url'		=> 'http://add',
	'valid_ip'		=> 'google.com'
);

$valid_data = array(
	'missing'   	=> 'This is not missing',
	'email'     	=> 'sean@wixel.net',
	'max_len'   	=> '1',
	'min_len'   	=> '1234',
	'exact_len' 	=> '1234567890',
	'alpha'	       	=> 'abcdefg',
	'alpha_numeric' => 'abcdefg12345',
	'alpha_dash'	=> 'abcdefg12345-_',
	'numeric'		=> 2.00,
	'integer'		=> 3,
	'boolean'		=> FALSE,
	'float'			=> 10.10,
	'valid_url'		=> 'http://wixel.net',
	'valid_ip'		=> '69.163.138.62'
);


echo "These all FAIL:\n";

print_r(GUMP::validate($invalid_data, $rules));

echo "These all SUCCEED:\n";

print_r(GUMP::validate($valid_data, $rules));

echo "DONE";