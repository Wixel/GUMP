<?php
/**
 * Generate appropriate PHPUnit configuration based on version
 */

// Detect PHPUnit version
$versionOutput = shell_exec('./vendor/bin/phpunit --version 2>/dev/null');
preg_match('/(\d+)\.(\d+)/', $versionOutput ?: '', $matches);

$majorVersion = isset($matches[1]) ? (int)$matches[1] : 9;
$minorVersion = isset($matches[2]) ? (int)$matches[2] : 0;

echo "Detected PHPUnit version: {$matches[0]}\n";

if ($majorVersion < 8) {
    // Generate legacy configuration for PHPUnit < 8
    $config = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<phpunit backupGlobals="false"
    convertWarningsToExceptions="true"
    convertNoticesToExceptions="true"
    convertErrorsToExceptions="true"
    convertDeprecationsToExceptions="true"
    bootstrap="tests/bootstrap.php"
    verbose="true"
    colors="true">
    
    <filter>
        <whitelist processUncoveredFiles="true">
            <file>gump.class.php</file>
        </whitelist>
    </filter>
    
    <testsuites>
        <testsuite name="default">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
    
    <logging>
        <log type="coverage-clover" target="build/logs/clover.xml"/>
    </logging>
</phpunit>
XML;
    echo "Generated legacy PHPUnit configuration\n";
} else {
    // Generate modern configuration for PHPUnit >= 8
    $config = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
    backupGlobals="false" 
    convertWarningsToExceptions="true" 
    convertNoticesToExceptions="true" 
    convertErrorsToExceptions="true" 
    convertDeprecationsToExceptions="true" 
    bootstrap="tests/bootstrap.php" 
    verbose="true" 
    colors="true"
    xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/9.3/phpunit.xsd">
    
    <coverage processUncoveredFiles="true">
        <include>
            <file>gump.class.php</file>
        </include>
        <report>
            <clover outputFile="build/logs/clover.xml"/>
        </report>
    </coverage>
    
    <testsuites>
        <testsuite name="default">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
    
    <logging/>
</phpunit>
XML;
    echo "Generated modern PHPUnit configuration\n";
}

// Write to temporary config file
$configFile = 'phpunit-runtime.xml';
file_put_contents($configFile, $config);

echo "Configuration written to: $configFile\n";
echo "Ready for coverage generation\n";