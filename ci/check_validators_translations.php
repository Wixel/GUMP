<?php
require_once 'ci/boot.php';

$validators = array_keys(get_gump_validators());

$languages = array_filter(scandir('lang'), function ($file) {
    return !in_array($file, ['.', '..']);
});

$totalMissingTranslations = 0;
$totalDeprecatedTranslations = 0;

foreach ($languages as $language) {

    $translations = array_map(function($item) {
        return str_replace('validate_', '', $item);
//    }, array_keys(require 'lang/en.php'));
    }, array_keys(require 'lang/'.$language));

    $missingTranslations = array_diff($validators, $translations);
    $deprecatedTranslations = array_diff($translations, $validators);

    $totalMissingTranslations += count($missingTranslations);
    $totalDeprecatedTranslations += count($deprecatedTranslations);

    if (count($missingTranslations) > 0 || count($deprecatedTranslations) > 0) {
        printf('Detected some issues in %s...'.PHP_EOL.PHP_EOL, $language);

        foreach ($missingTranslations as $missingTranslation) {
            print(sprintf('⮕ "%s" error message is missing!', $missingTranslation).PHP_EOL);
        }

        foreach ($deprecatedTranslations as $deprecatedTranslation) {
            print(sprintf('⮕ "%s" error message is deprecated!', $deprecatedTranslation).PHP_EOL);
        }

        printf(PHP_EOL.'Please add the missing or remove the outdated translations in lang/%s file.'.PHP_EOL, $language);

        echo '======================================================'.PHP_EOL.PHP_EOL;
    }
}

if ($totalMissingTranslations > 0 || $totalDeprecatedTranslations > 0) {
    printf('%d translations missing and %d deprecated'.PHP_EOL, $totalMissingTranslations, $totalDeprecatedTranslations);
    exit(1);
}

print('Translation checks successfully passed!'.PHP_EOL);
