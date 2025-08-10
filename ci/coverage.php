<?php
/**
 * Coverage runner script that handles environments with or without coverage drivers
 */

echo "Checking for code coverage driver...\n";

// Check if coverage drivers are available
$hasCoverage = extension_loaded('xdebug') || extension_loaded('pcov');

if ($hasCoverage) {
    echo "✅ Coverage driver found. Running tests with coverage...\n";
    
    // Create build directory
    if (!is_dir('build/logs')) {
        mkdir('build/logs', 0755, true);
    }
    
    // Generate appropriate PHPUnit configuration for the detected version
    echo "Generating version-appropriate PHPUnit configuration...\n";
    system('php ci/generate_phpunit_config.php', $configResult);
    
    if ($configResult === 0 && file_exists('phpunit-runtime.xml')) {
        $configFile = 'phpunit-runtime.xml';
        echo "Using generated configuration: {$configFile}\n";
    } else {
        echo "⚠️  Could not generate configuration, falling back to default\n";
        $configFile = 'phpunit.xml.dist';
    }
    
    // Run tests with coverage using appropriate config
    $cmd = "./vendor/bin/phpunit --configuration=\"{$configFile}\" --coverage-clover=build/logs/clover.xml";
    $result = 0;
    passthru($cmd, $result);
    
    // Clean up runtime config
    @unlink('phpunit-runtime.xml');
    
    if ($result !== 0) {
        echo "❌ Test execution failed\n";
        exit($result);
    }
    
    if (file_exists('build/logs/clover.xml')) {
        echo "✅ Coverage report generated. Uploading to Coveralls...\n";
        
        // Run coveralls
        $coverallsCmd = 'php vendor/bin/php-coveralls --exclude-no-stmt --coverage_clover=build/logs/clover.xml -v';
        passthru($coverallsCmd, $result);
        
        exit($result);
    } else {
        echo "❌ Coverage report not generated despite coverage driver being available\n";
        exit(1);
    }
} else {
    echo "⚠️  No code coverage driver available (xdebug/pcov). Skipping coverage reporting.\n";
    echo "Running tests without coverage...\n";
    
    // Run tests without coverage
    $cmd = './vendor/bin/phpunit';
    $result = 0;
    passthru($cmd, $result);
    
    if ($result !== 0) {
        echo "❌ Test execution failed\n";
        exit($result);
    }
    
    // Create build directory
    if (!is_dir('build/logs')) {
        mkdir('build/logs', 0755, true);
    }
    
    // Create placeholder coverage file
    $timestamp = time();
    $placeholderXml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<coverage generated="{$timestamp}">
  <project timestamp="{$timestamp}">
    <!-- No coverage data available - coverage driver not installed -->
    <!-- To enable real coverage, install xdebug or pcov extension -->
  </project>
</coverage>
XML;
    
    file_put_contents('build/logs/clover.xml', $placeholderXml);
    
    echo "✅ Created placeholder coverage file for Coveralls compatibility\n";
    echo "ℹ️  To enable real coverage, install xdebug or pcov extension\n";
}

echo "Coverage workflow completed successfully\n";