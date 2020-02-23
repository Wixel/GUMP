<?php
require_once 'vendor/autoload.php';

use kamermans\Reflection\DocBlock;

$reflect = new ReflectionClass("GUMP");

$validators = array_filter($reflect->getMethods(), function($method) {
    return strpos($method->name, 'validate_') !== false;
});

$rows = array_map(function($method) {
    $docblock = new DocBlock($method);

    $ruleDescription = $docblock->getComment();
    $ruleExampleParameter = $docblock->getTag('example_parameter');

    $item = [];

    if (!is_null($ruleExampleParameter)) {
        $item[] = sprintf('*%s*,%s', $method->name, $ruleExampleParameter);
    } else {
        $item[] = sprintf('*%s*', $method->name);
    }

    $item[] = $ruleDescription;

    return $item;
}, $validators);


$tableBuilder = new \MaddHatter\MarkdownTable\Builder();

// add some data
$tableBuilder
	->headers(['Rule','Description'])
	->align(['L','L'])
	->rows($rows);
//	->row(['col 3 is', 'right-aligned', '$1']); // add a single row

$readme = file_get_contents('README.md');

//var_dump($readme);

//echo $tableBuilder->render();
