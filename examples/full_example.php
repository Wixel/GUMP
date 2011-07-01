#!/usr/bin/php -q
<?php

require "../gump.class.php";

// Set the data

$_POST = array(
	'username' 	  => 'SeanNieuwoudt',
	'password' 	  => 'mypassword',
	'email'	      => 'sean@wixel.net',
	'gender'   	  => 'm',
	'credit_card' => '9872389-2424-234224-234', // Obviously an invalid credit card number,
	'bio'		  => 'This is good! I think I will switch to another language'
);

$_POST = GUMP::sanitize($_POST); // You don't have to sanitize, but it's safest to do so.

// Let's define the rules and filters

$rules = array(
	'username'    => 'required|alpha_numeric|max_len,100|min_len,6',
	'password'    => 'required|max_len,100|min_len,6',
	'email'       => 'required|valid_email',
	'gender'      => 'required|exact_len,1',
	'credit_card' => 'required|valid_cc',
	'bio'		  => 'required'
);

$filters = array(
	'username' 	  => 'trim|sanitize_string',
	'password'	  => 'trim|base64_encode',
	'email'    	  => 'trim|sanitize_email',
	'gender'   	  => 'trim',
	'bio'		  => 'translate,en,de'
);

$_POST = GUMP::filter($_POST, $filters);

// You can run filter() or validate() first

$validated = GUMP::validate(
	$_POST, $rules
);

// Check if validation was successful

if($validated === TRUE)
{
	echo "Successful Validation\n\n";
	
	print_r($_POST); // You can now use POST data safely
	
	exit;
}
else
{
	print_r($_POST); 
		
	print_r($validated); // Shows all the rules that failed along with the data
}
