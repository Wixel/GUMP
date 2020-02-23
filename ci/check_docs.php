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
        $errors[] = sprintf('Docs rule value is "%s" but it should be "%s"', $docs[$key]['rule'], $value['rule']);
    }

    if ($value['description'] !== $docs[$key]['description']) {
        $errors[] = sprintf('Docs description value is "%s" but it should be "%s"', $docs[$key]['description'], $value['description']);
    }
}

if (count($errors) > 0) {
    foreach ($errors as $error) {
        print(sprintf('⮕ %s', $error).PHP_EOL);
    }

    trigger_error('Run "php ci/dump_docs.php" to fix this!');
    exit(1);
} else {
    print('Docs checks succesfully passed!'.PHP_EOL);
}
