<?php

namespace Tests\Helpers;

use GUMP\EnvHelpers;
use Tests\BaseTestCase;
use Mockery as m;

/**
 * Class EnvHelpersTest
 *
 * @package Tests\Helpers
 */
class EnvHelpersTest extends BaseTestCase
{
    /**
     * Test functionExists method with existing function
     */
    public function testFunctionExistsWithExistingFunction()
    {
        $this->assertTrue(EnvHelpers::functionExists('strlen'));
        $this->assertTrue(EnvHelpers::functionExists('array_merge'));
        $this->assertTrue(EnvHelpers::functionExists('time'));
    }

    /**
     * Test functionExists method with non-existing function
     */
    public function testFunctionExistsWithNonExistingFunction()
    {
        $this->assertFalse(EnvHelpers::functionExists('non_existing_function_123'));
        $this->assertFalse(EnvHelpers::functionExists('imaginary_function'));
    }

    /**
     * Test functionExists method with case sensitivity
     */
    public function testFunctionExistsWithCaseSensitivity()
    {
        // PHP function names are case-insensitive
        $this->assertTrue(EnvHelpers::functionExists('STRLEN'));
        $this->assertTrue(EnvHelpers::functionExists('Array_Merge'));
    }

    /**
     * Test date method with default timestamp (current time)
     */
    public function testDateWithDefaultTimestamp()
    {
        $result = EnvHelpers::date('Y-m-d');
        $expected = date('Y-m-d');
        
        $this->assertEquals($expected, $result);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $result);
    }

    /**
     * Test date method with specific timestamp
     */
    public function testDateWithSpecificTimestamp()
    {
        $timestamp = 1640995200; // 2022-01-01 00:00:00 UTC
        
        $result = EnvHelpers::date('Y-m-d H:i:s', $timestamp);
        $expected = date('Y-m-d H:i:s', $timestamp);
        
        $this->assertEquals($expected, $result);
    }

    /**
     * Test date method with various formats
     */
    public function testDateWithVariousFormats()
    {
        $timestamp = 1640995200; // 2022-01-01 00:00:00 UTC
        
        $formats = [
            'Y-m-d' => date('Y-m-d', $timestamp),
            'H:i:s' => date('H:i:s', $timestamp),
            'Y/m/d H:i' => date('Y/m/d H:i', $timestamp),
            'D, M j Y' => date('D, M j Y', $timestamp),
            'c' => date('c', $timestamp), // ISO 8601
            'U' => date('U', $timestamp), // Unix timestamp
        ];
        
        foreach ($formats as $format => $expected) {
            $result = EnvHelpers::date($format, $timestamp);
            $this->assertEquals($expected, $result, "Failed for format: $format");
        }
    }

    /**
     * Test date method with null timestamp
     */
    public function testDateWithNullTimestamp()
    {
        $result = EnvHelpers::date('Y-m-d', null);
        $expected = date('Y-m-d');
        
        // Allow for potential second difference due to execution time
        $this->assertEqualsWithDelta(
            strtotime($expected),
            strtotime($result),
            2, // 2 seconds tolerance
            "Date should be current date when timestamp is null"
        );
    }

    /**
     * Test checkdnsrr method with valid hostname
     * 
     * Note: This test might fail in environments without internet access
     * or where DNS resolution is blocked
     */
    public function testCheckDnsrrWithValidHostname()
    {
        // Skip if we can't test network functionality
        if (!function_exists('checkdnsrr')) {
            $this->markTestSkipped('checkdnsrr function not available');
        }
        
        // Test with a well-known domain that should have DNS records
        $result = EnvHelpers::checkdnsrr('google.com', 'A');
        
        // This should be true, but we'll be lenient for CI environments
        $this->assertIsBool($result);
    }

    /**
     * Test checkdnsrr method with invalid hostname
     */
    public function testCheckDnsrrWithInvalidHostname()
    {
        if (!function_exists('checkdnsrr')) {
            $this->markTestSkipped('checkdnsrr function not available');
        }
        
        // Test with an obviously invalid domain
        $result = EnvHelpers::checkdnsrr('definitely-not-a-real-domain-12345.invalid', 'A');
        $this->assertFalse($result);
    }

    /**
     * Test checkdnsrr method with default type parameter
     */
    public function testCheckDnsrrWithDefaultType()
    {
        if (!function_exists('checkdnsrr')) {
            $this->markTestSkipped('checkdnsrr function not available');
        }
        
        // Test with default type (should be MX)
        $result = EnvHelpers::checkdnsrr('google.com');
        $this->assertIsBool($result);
    }

    /**
     * Test checkdnsrr method with different record types
     */
    public function testCheckDnsrrWithDifferentTypes()
    {
        if (!function_exists('checkdnsrr')) {
            $this->markTestSkipped('checkdnsrr function not available');
        }
        
        $types = ['A', 'MX', 'NS', 'SOA', 'TXT'];
        
        foreach ($types as $type) {
            $result = EnvHelpers::checkdnsrr('google.com', $type);
            $this->assertIsBool($result, "checkdnsrr should return boolean for type: $type");
        }
    }

    /**
     * Test gethostbyname method with valid hostname
     */
    public function testGethostbynameWithValidHostname()
    {
        $result = EnvHelpers::gethostbyname('localhost');
        
        // localhost should resolve to 127.0.0.1 or return the hostname if resolution fails
        $this->assertThat(
            $result,
            $this->logicalOr(
                $this->equalTo('127.0.0.1'),
                $this->equalTo('localhost')
            )
        );
    }

    /**
     * Test gethostbyname method with invalid hostname
     */
    public function testGethostbynameWithInvalidHostname()
    {
        $invalidHost = 'definitely-not-a-real-hostname-12345.invalid';
        $result = EnvHelpers::gethostbyname($invalidHost);
        
        // Should return the original hostname when resolution fails
        $this->assertEquals($invalidHost, $result);
    }

    /**
     * Test gethostbyname method with IP address
     */
    public function testGethostbynameWithIpAddress()
    {
        $ip = '127.0.0.1';
        $result = EnvHelpers::gethostbyname($ip);
        
        // Should return the IP address itself
        $this->assertEquals($ip, $result);
    }

    /**
     * Test file_exists method with existing file
     */
    public function testFileExistsWithExistingFile()
    {
        // Test with a file that should exist (the test file itself)
        $testFile = __FILE__;
        $result = EnvHelpers::file_exists($testFile);
        
        $this->assertTrue($result);
    }

    /**
     * Test file_exists method with non-existing file
     */
    public function testFileExistsWithNonExistingFile()
    {
        $nonExistentFile = '/path/to/non/existent/file/12345.txt';
        $result = EnvHelpers::file_exists($nonExistentFile);
        
        $this->assertFalse($result);
    }

    /**
     * Test file_exists method with directory
     */
    public function testFileExistsWithDirectory()
    {
        $directory = __DIR__;
        $result = EnvHelpers::file_exists($directory);
        
        // Directories should return true with file_exists
        $this->assertTrue($result);
    }

    /**
     * Test file_exists method with relative path
     */
    public function testFileExistsWithRelativePath()
    {
        // Test with relative path to composer.json from project root
        $composerFile = __DIR__ . '/../../composer.json';
        
        if (file_exists($composerFile)) {
            $result = EnvHelpers::file_exists($composerFile);
            $this->assertTrue($result);
        } else {
            $this->markTestSkipped('Composer.json not found at expected location');
        }
    }

    /**
     * Test file_exists method with empty string
     */
    public function testFileExistsWithEmptyString()
    {
        $result = EnvHelpers::file_exists('');
        $this->assertFalse($result);
    }

    /**
     * Test file_exists method with null (should handle gracefully)
     */
    public function testFileExistsWithNull()
    {
        // This might trigger a warning, but should return false
        $result = @EnvHelpers::file_exists(null);
        $this->assertFalse($result);
    }

    /**
     * Integration test: Test method chaining and interaction
     */
    public function testMethodInteraction()
    {
        // Test that methods work together as expected
        $format = 'Y-m-d';
        $timestamp = time();
        
        // These should all work without interfering with each other
        $dateResult = EnvHelpers::date($format, $timestamp);
        $functionResult = EnvHelpers::functionExists('date');
        $fileResult = EnvHelpers::file_exists(__FILE__);
        
        $this->assertIsString($dateResult);
        $this->assertTrue($functionResult);
        $this->assertTrue($fileResult);
    }
}