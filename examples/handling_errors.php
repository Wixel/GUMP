#!/usr/bin/php -q
<?php

require "../gump.class.php";

$validator = new GUMP();

// Set the data

$_POST = array(
	'username' 	  => 'SeanNieuwoudt',
	'password' 	  => 'mypassword',
	'email'	      => 'sean@wixel.net',
	'gender'   	  => 'm',
	'credit_card' => '9872389-2424-234224-234', // Obviously an invalid credit card number,
	'bio'		  => 'This is good! I think I will switch to another language'
);

$_POST = $validator->sanitize($_POST); // You don't have to sanitize, but it's safest to do so.

// Let's define the rules and filters

$rules = array(
	'username'    => 'required|alpha_numeric|max_len,100|min_len,40',
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
	'gender'   	  => 'trim'
);

$_POST = $validator->filter($_POST, $filters);

// You can run filter() or validate() first

$validated = $validator->validate(
	$_POST, $rules
);

if($validated === TRUE)
{
	echo "Successful Validation\n\n";
	
	print_r($_POST); // You can now use POST data safely
	
	exit;
}
else
{
	// You should know what form fields to expect, so you can reference them here for custom messages
	echo "There were errors with the data you provided:\n";
	
	foreach($validated as $v) {
		switch($v['field']) {
			case 'credit_card':
				echo "- The credit card provided is not valid.\n";
				break;
			case 'username':
				echo "- The username provided is not valid.\n";
				break;				
		}
	}
	
	// Or you can simply use the built in helper to generate the error messages for you
	// Passing a boolean true to is returns the errors as html, otherwise it returns an array
	echo $validator->get_readable_errors(true);
}
