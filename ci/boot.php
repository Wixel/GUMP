<?php
require_once 'vendor/autoload.php';

const README_FILE = 'README.md';

const VALIDATORS_PREFIX = 'validate_';
const FILTERS_PREFIX = 'filter_';

function get_gump_validators() {
    $reflect = new ReflectionClass("GUMP");

    $validators = array_filter($reflect->getMethods(), function($method) {
        return strpos($method->name, VALIDATORS_PREFIX) !== false;
    });

    $result = [];
    foreach ($validators as $validator) {
        $docblock = new \kamermans\Reflection\DocBlock($validator);

        $ruleName = str_replace(VALIDATORS_PREFIX, '', $validator->name);
        $ruleDescription = $docblock->getComment();
        $ruleExampleParameter = $docblock->getTag('example_parameter');

        $item = [
            'description' => $ruleDescription
        ];

        if (!is_null($ruleExampleParameter)) {
            $item['rule'] = sprintf('**%s**,%s', $ruleName, $ruleExampleParameter);
        } else {
            $item['rule'] = sprintf('**%s**', $ruleName);
        }

        $result[$ruleName] = $item;
    }

    return $result;
}

function get_docs_validators(string $readmePath) {
    $readmeContents = file_get_contents($readmePath);

    preg_match_all('/<div id="available_validators">(.*?)<.div>/ms', $readmeContents, $outerMatches);

    $regex = '/^\| (.*?) \| (.*) \|\n/m';
    preg_match_all($regex, $outerMatches[1][0], $matches);

    // remove first row (rule, description)
    unset($matches[1][0]);

    $result = [];
    foreach ($matches[1] as $key => $value) {

        $rawRule = trim($value);
        preg_match('/\*\*(.*?)\*\*/', $rawRule, $ruleMatch);

        $result[$ruleMatch[1]] = [
            'rule' => $rawRule,
            'description' => trim($matches[2][$key])
        ];
    }

    return $result;
}

function get_gump_filters() {
    $reflect = new ReflectionClass("GUMP");

    $methodsToIgnore = ['filter_input', 'filter_rules', 'filter_to_method'];

    $filters = array_filter($reflect->getMethods(), function($method) use($methodsToIgnore) {
        return strpos($method->name, FILTERS_PREFIX) !== false && !in_array($method->name, $methodsToIgnore);
    });

    $result = [];
    foreach ($filters as $filter) {
        $docblock = new \kamermans\Reflection\DocBlock($filter);

        $ruleName = str_replace(FILTERS_PREFIX, '', $filter->name);
        $ruleDescription = $docblock->getComment();
        $ruleExampleParameter = $docblock->getTag('example_parameter');

        $item = [
            'description' => $ruleDescription
        ];

        if (!is_null($ruleExampleParameter)) {
            $item['rule'] = sprintf('**%s**,%s', $ruleName, $ruleExampleParameter);
        } else {
            $item['rule'] = sprintf('**%s**', $ruleName);
        }

        $result[$ruleName] = $item;
    }

    return $result;
}


function get_docs_filters(string $readmePath) {
    $readmeContents = file_get_contents($readmePath);

    preg_match_all('/<div id="available_filters">(.*?)<.div>/ms', $readmeContents, $outerMatches);

    $regex = '/^\| (.*?) \| (.*) \|\n/m';
    preg_match_all($regex, $outerMatches[1][0], $matches);

    // remove first row (rule, description)
    unset($matches[1][0]);

    $result = [];
    foreach ($matches[1] as $key => $value) {

        $rawRule = trim($value);
        preg_match('/\*\*(.*?)\*\*/', $rawRule, $ruleMatch);

        $result[$ruleMatch[1]] = [
            'rule' => $rawRule,
            'description' => trim($matches[2][$key])
        ];
    }

    return $result;
}

