#!/usr/bin/php -q
<?php

require "../gump.class.php";

$validator = new GUMP();

$_POST = array(
	'first_name' 	=> 'Joe',
	'last_name'	=> 'Black',
	'nickname'	=> 'blackjoe', // unexpected field
);

$rules = array(
	'first_name' 	=> 'required|valid_name',
	'last_name' 	=> 'required|valid_name'
);


/**
 * You can "whitelist" the submitted fileds: other fields will be ignored.
 * Pass an array of fields as 2nd argument in 'sanitize' method, e.g.:
 * $whitelist = array( 'first_name', 'last_name' );
 * 
 * Tip: you can use the keys of rule/filter array as a whitelist
 */
$whitelist = array_keys($rules);
$_POST = $validator->sanitize( $_POST, $whitelist );

$validated = $validator->validate($_POST, $rules);

if( $validated === TRUE ) 
{
	/**
	 * Now you are sure that the $_POST array contains only the fields 
	 * included in whitelist.
	 * 
	 * It's a good practice anyway, but it's very useful if you are 
	 * using an ORM/active-records library to store data into database
	 * and you have to be sure that the fields match the table columns.
	 * 
	 * E.g.: ... $db->table('products')->insert($_POST) ...
	 */
	print_r($_POST); 
}
