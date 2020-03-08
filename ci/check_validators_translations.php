<?php
require_once 'ci/boot.php';

$validators = array_keys(get_gump_validators());
$translations = array_map(function($item) {
    return str_replace('validate_', '', $item);
}, array_keys(require 'lang/en.php'));


$missingTranslations = array_diff($validators, $translations);

if (count($missingTranslations) > 0) {
    foreach ($missingTranslations as $missingTranslation) {
        print(sprintf('⮕ %s error message is missing!', $missingTranslation).PHP_EOL);
    }

    print(PHP_EOL.'Please add missing translations to lang/en.php file.'.PHP_EOL);
    exit(1);
} else {
    print('Translation checks successfully passed!'.PHP_EOL);
}