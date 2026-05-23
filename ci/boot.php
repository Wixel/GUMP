<?php

require_once 'vendor/autoload.php';

const README_FILE = 'README.md';

const VALIDATORS_PREFIX = 'validate_';
const FILTERS_PREFIX = 'filter_';

function get_gump_validators()
{
    // Walk the registry — the source of truth post-modernisation.
    $registry = call_gump_internal('registry');

    $registryReflection = new ReflectionClass($registry);
    $validatorsProperty = $registryReflection->getProperty('validators');
    $validatorsProperty->setAccessible(true);
    /** @var array<string, GUMP\Validation\Validator> $validators */
    $validators = $validatorsProperty->getValue($registry);

    $result = [];
    foreach ($validators as $ruleName => $validator) {
        $classReflection = new ReflectionClass($validator);

        $description = parse_class_docblock_description($classReflection->getDocComment());

        $item = ['description' => $description];

        if ($classReflection->hasConstant('EXAMPLE_PARAMETER')) {
            $item['rule'] = sprintf('**%s**,%s', $ruleName, $classReflection->getConstant('EXAMPLE_PARAMETER'));
        } else {
            $item['rule'] = sprintf('**%s**', $ruleName);
        }

        $result[$ruleName] = $item;
    }

    return $result;
}

/**
 * Invoke a protected static method on GUMP (used to reach into registry() / filter_registry()
 * which are non-public by design).
 */
function call_gump_internal(string $method)
{
    $reflection = new ReflectionClass('GUMP');
    $methodReflection = $reflection->getMethod($method);
    $methodReflection->setAccessible(true);

    return $methodReflection->invoke(null);
}

/**
 * Extract the first sentence/line of the class docblock as the rule's description.
 */
function parse_class_docblock_description($docComment): string
{
    if ($docComment === false || $docComment === '') {
        return '';
    }

    // Strip the /** and */ wrappers, then take the first non-empty content line.
    $stripped = preg_replace('#^\s*/\*\*\s*\n?|\s*\*/\s*$#', '', $docComment);
    $lines = preg_split('/\R/', $stripped);

    foreach ($lines as $line) {
        $line = preg_replace('/^\s*\*\s?/', '', $line);
        $line = trim($line);
        if ($line !== '' && !str_starts_with($line, '@')) {
            return $line;
        }
    }

    return '';
}

function get_docs_validators(string $readmePath)
{
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
            'description' => trim($matches[2][$key]),
        ];
    }

    return $result;
}

function get_gump_filters()
{
    $registry = call_gump_internal('filter_registry');

    $registryReflection = new ReflectionClass($registry);
    $filtersProperty = $registryReflection->getProperty('filters');
    $filtersProperty->setAccessible(true);
    /** @var array<string, GUMP\Filtering\Filter> $filters */
    $filters = $filtersProperty->getValue($registry);

    $result = [];
    foreach ($filters as $ruleName => $filter) {
        $classReflection = new ReflectionClass($filter);

        $description = parse_class_docblock_description($classReflection->getDocComment());

        $item = ['description' => $description];

        if ($classReflection->hasConstant('EXAMPLE_PARAMETER')) {
            $item['rule'] = sprintf('**%s**,%s', $ruleName, $classReflection->getConstant('EXAMPLE_PARAMETER'));
        } else {
            $item['rule'] = sprintf('**%s**', $ruleName);
        }

        $result[$ruleName] = $item;
    }

    return $result;
}

function get_docs_filters(string $readmePath)
{
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
            'description' => trim($matches[2][$key]),
        ];
    }

    return $result;
}
