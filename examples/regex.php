#!/usr/bin/php -q
<?php

require "../gump.class.php";

$gump = new GUMP();

$data = array(
    'street' => '2',
    'name' => 'a',
    'number' => '8'
);

$validated = GUMP::is_valid($data, array(
    'street' => 'regex,/^[0|2]+$/',
    'name' => 'required|regex,/^[a-z]+$/',
    'number' => 'required|alpha_numeric',
    
));

if($validated === true) {
    echo "Good";
} else {
	echo '<pre>';
    print_r($validated);
    echo '</pre>';
}
