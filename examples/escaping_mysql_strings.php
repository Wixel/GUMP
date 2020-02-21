#!/usr/bin/php -q
<?php

require "../gump.class.php";

$validator = new GUMP();

$_POST = array(
    'username' => "my username",
    'password' => "' OR ''='"
);

$validator->sanitize($_POST);

$filters = array(
    'username' => 'noise_words',
    'password' => 'trim|strtolower|addslashes'
);

print_r($validator->filter($_POST, $filters));