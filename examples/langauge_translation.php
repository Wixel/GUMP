#!/usr/bin/php -q
<?php

require "../gump.class.php";

$_POST = array(
	'sentence_1' => "CLICK one of the Column Titles to sort the table by that item. This table does not include mostly ancient languages that do not have assigned two letter codes.",
	'sentence_2' => "Using this function will use any registered autoloaders if the class is not already known."
);

/*
* Most well known ISO 639-1 2 character language codes may be used 
*
* See: http://www.science.co.il/language/Codes.asp?s=code2
*/

$filters = array(
	'sentence_1' => 'translate,en,de',
	'sentence_2' => 'translate,en,cs',
);

print_r(GUMP::filter($_POST, $filters));
