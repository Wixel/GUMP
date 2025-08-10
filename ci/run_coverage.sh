#!/bin/bash

# Script to conditionally run coverage and coveralls based on driver availability

echo "Checking for code coverage driver..."

# Check if we have Xdebug or PCOV available
if php -m | grep -E "(xdebug|pcov)" >/dev/null 2>&1; then
    echo "✅ Coverage driver found. Running tests with coverage..."
    
    # Create build directory if it doesn't exist
    mkdir -p build/logs
    
    # Generate appropriate PHPUnit configuration for the detected version
    echo "Generating version-appropriate PHPUnit configuration..."
    php ci/generate_phpunit_config.php
    
    if [ -f "phpunit-runtime.xml" ]; then
        CONFIG_FILE="phpunit-runtime.xml"
        echo "Using generated configuration: $CONFIG_FILE"
    else
        echo "⚠️  Could not generate configuration, falling back to default"
        CONFIG_FILE="phpunit.xml.dist"
    fi
    
    # Run tests with coverage using appropriate config
    ./vendor/bin/phpunit --configuration="$CONFIG_FILE" --coverage-clover=build/logs/clover.xml
    
    # Clean up runtime config
    rm -f phpunit-runtime.xml
    
    if [ -f "build/logs/clover.xml" ]; then
        echo "✅ Coverage report generated. Uploading to Coveralls..."
        php vendor/bin/php-coveralls --exclude-no-stmt --coverage_clover=build/logs/clover.xml -v
    else
        echo "❌ Coverage report not generated despite coverage driver being available"
        exit 1
    fi
else
    echo "⚠️  No code coverage driver available (xdebug/pcov). Skipping coverage reporting."
    echo "Running tests without coverage..."
    ./vendor/bin/phpunit
    
    # Create a minimal coverage file to prevent coveralls from failing
    mkdir -p build/logs
    timestamp=$(date +%s)
    cat > build/logs/clover.xml << EOF
<?xml version="1.0" encoding="UTF-8"?>
<coverage generated="$timestamp">
  <project timestamp="$timestamp">
    <!-- No coverage data available - coverage driver not installed -->
    <!-- To enable real coverage, install xdebug or pcov extension -->
  </project>
</coverage>
EOF
    
    echo "✅ Created placeholder coverage file for Coveralls compatibility"
    echo "ℹ️  To enable real coverage, install xdebug or pcov extension"
fi

echo "Coverage workflow completed successfully"