#!/usr/bin/php -q
<?php

require "../gump.class.php";

$validator = new GUMP();

$validator->validation_rules(array(
	'comment' => 'required|max_len,500',
));

$validator->filter_rules(array(
	'comment' => 'basic_tags',
));

// Valid Data
$_POST = array(
	'comment' => '<strong>this is freaking awesome</strong><script>alert(1);</script>'
);


$_POST = $validator->run($_POST);

print_r($_POST);