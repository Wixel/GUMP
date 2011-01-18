#!/usr/bin/php -q
<?php

echo htmlentities('<script>alert(1);</script>');

require "gump.class.php";

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
	'email'     	=> 'not a valid email\n\n',
	'max_len'   	=> '1234567890\n\n',
	'min_len'   	=> '1\n\n',
	'exact_len' 	=> '123456\n\n',
	'alpha'	       	=> '*(^*^*&\n\n',
	'alpha_numeric' => 'abcdefg12345+\n\n',
	'alpha_dash'	=> 'ab<script>alert(1);</script>cdefg12345-_+\n\n',
	'numeric'		=> 'one, two\n\n',
	'integer'		=> '1,003\n\n',
	'boolean'		=> 'this is not a boolean\r\n',
	'float'			=> 'not a float\n\n',
	'valid_url'		=> 'http://add\n\n',
	'valid_ip'		=> 'google.com\n\n'
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

echo "\nBEFORE SANITIZE:\n\n";

print_r($invalid_data);

echo "\nAFTER SANITIZE:\n\n";

print_r(GUMP::sanitize($invalid_data));

echo "\nTHESE ALL FAIL:\n\n";

print_r(GUMP::validate($invalid_data, $rules));

if(GUMP::validate($valid_data, $rules))
{
  echo "\nTHESE ALL SUCCEED:\n\n";
  
  print_r($valid_data);
}

echo "\nDONE\n\n";