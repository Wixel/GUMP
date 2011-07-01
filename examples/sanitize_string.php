#!/usr/bin/php -q
<?php

require "../gump.class.php";

$_POST = array(
	'string' => '<script>alert(1); $("body").remove(); </script>'
);

$filters = array(
	'string' => 'sanitize_string'
);

print_r(GUMP::filter($_POST, $filters));
