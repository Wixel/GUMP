<?php
require_once 'ci/boot.php';

$docs = get_docs_validators(README_FILE);
$gump = get_gump_validators();

$errors = [];

foreach ($gump as $key => $value) {
    if (!isset($docs[$key])) {
        $errors[] = sprintf('"%s" validator exists in GUMP but not in docs!', $key);
        continue;
    }

    if ($value['rule'] !== $docs[$key]['rule']) {
        $errors[] = sprintf('Docs "%s" validator value is "%s" but it should be "%s"', $key, $docs[$key]['rule'], $value['rule']);
    }

    if ($value['description'] !== $docs[$key]['description']) {
        $errors[] = sprintf('Docs "%s" validator description is "%s" but it should be "%s"', $key, $docs[$key]['description'], $value['description']);
    }
}

if (count($errors) > 0) {
    foreach ($errors as $error) {
        print(sprintf('⮕ %s', $error).PHP_EOL);
    }

    print(PHP_EOL.'Run "php ci/dump_validators_docs.php" to fix this!'.PHP_EOL);
    exit(1);
} else {
    print('Docs checks successfully passed for validators!'.PHP_EOL);
}
