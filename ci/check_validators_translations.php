<?php
require_once 'ci/boot.php';

$validators = array_keys(get_gump_validators());

$languages = array_filter(scandir('lang'), function ($file) {
    return !in_array($file, ['.', '..']);
});

$totalMissingTranslations = 0;

foreach ($languages as $language) {
    printf('Checking validators for language %s...'.PHP_EOL.PHP_EOL, $language);

    $translations = array_map(function($item) {
        return str_replace('validate_', '', $item);
    }, array_keys(require 'lang/'.$language));

    $missingTranslations = array_diff($validators, $translations);

    $totalMissingTranslations += count($missingTranslations);

    if (count($missingTranslations) > 0) {
        foreach ($missingTranslations as $missingTranslation) {
            print(sprintf('⮕ %s error message is missing!', $missingTranslation).PHP_EOL);
        }

        print(PHP_EOL.'Please add missing translations to lang/en.php file.'.PHP_EOL);
    } else {
        print('Translation checks successfully passed!'.PHP_EOL);
    }

    echo '======================================================'.PHP_EOL.PHP_EOL;
}

if ($totalMissingTranslations > 0) {
    printf('%d translations missing'.PHP_EOL, $totalMissingTranslations);
    exit(1);
}
