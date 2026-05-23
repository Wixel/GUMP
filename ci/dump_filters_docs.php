<?php

require_once 'ci/boot.php';

$rows = array_map(function ($v) {
    return [$v['rule'], $v['description']];
}, get_gump_filters());

$tableBuilder = new \MaddHatter\MarkdownTable\Builder();

$tableBuilder
    ->headers(['Filter','Description'])
    ->align(['L','L'])
    ->rows($rows);

$readme = file_get_contents(README_FILE);

$regex = '/(?<=<div id="available_filters">)(.*?)(?=<.div>)/ms';

$replaced = preg_replace($regex, PHP_EOL.PHP_EOL.$tableBuilder->render(), $readme);

file_put_contents(README_FILE, $replaced);

print('Filters docs updated!'.PHP_EOL);
