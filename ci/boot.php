<?php
require_once 'vendor/autoload.php';

const README_FILE = 'README.md';

function get_gump_validators() {
    $reflect = new ReflectionClass("GUMP");

    $validators = array_filter($reflect->getMethods(), function($method) {
        return strpos($method->name, 'validate_') !== false;
    });

    $result = [];
    foreach ($validators as $validator) {
        $docblock = new \kamermans\Reflection\DocBlock($validator);

        $ruleName = str_replace('validate_', '', $validator->name);
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

    $regex = '/^\| (.*?) \| (.*) \|\n/m';
    preg_match_all($regex, $readmeContents, $matches);

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
